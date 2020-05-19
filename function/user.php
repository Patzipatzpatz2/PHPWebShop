<?php
function getCurrentUserId() {
  $userId = random_int(0, time());
  if (isset($_COOKIE['userId'])) {
    $userId = (int) $_COOKIE['userId'];
  }
  if (isset($_SESSION['userId'])) {
    $userId = (int) $_SESSION['userId'];
  }
  return $userId;
}
function getUserDataForUsername(string $username):array {
  $sql = "SELECT id, password FROM users WHERE name=:username";
  $statement = getDb()->prepare($sql);
  if ($statement === false)
    return [];
  $statement->execute([':username'=>$username]);
  if ($statement->rowCount() === 0)
    return [];
  $row = $statement->fetch();
  return $row;
}
function getAdressForUserId(int $id):array {
  $sql = "SELECT inhabitant, zip_code, city, street
          FROM users, adress
          WHERE users.adress_id = adress.id AND users.id=:id";
  $statement = getDb()->prepare($sql);
  if ($statement === false)
    return [];
  $statement->execute([':id'=>$id]);
  if ($statement->rowCount() === 0)
    return array("city"=>"", "inhabitant"=>"", "zip_code"=>"", "street"=>"");
  $row = $statement->fetch();
  return $row;
}
function createUser($username, $password, $mail):int {
  $sql = "INSERT INTO users SET name=:username, password=:password, mail=:mail";
  $statement = getDb()->prepare($sql);
  return $statement->execute([':username'=>$username, ':password'=>$password, ':mail'=>$mail]);
}
function setUserAdress($recipient, $zip, $city, $street) {
  //Adresse in Datenbank schreiben. To-Do: ON DUPLICATE KEY (?)
  //and adress_id in user_id auf .. setzen
  $sql = "INSERT INTO adress SET inhabitant=:recipient, country=:country, zip_code=:zip, city=:city, street=:street
          ON DUPLICATE KEY UPDATE id=id;

          UPDATE users SET adress_id=(SELECT id FROM adress WHERE inhabitant=:recipient AND city=:city AND street=:street)
          WHERE id=:userId";
  $statement = getDb()->prepare($sql);
  $statement->execute([':recipient'=>$recipient, ':country'=>"Germany", ':zip'=>$zip, ':city'=>$city, ':street'=>$street, ':userId'=>$_SESSION['userId']]);
}

//$baseURL: für redirect; $delivery: ob Adresse für Lieferung oder für User festgelegt wird
function userAdressSetting($baseURL, $delivery):array {
  //Post-Request: Formular abgeschickt
  $recipient = "Hans Wurst";
  $isPost = strtoupper($_SERVER['REQUEST_METHOD'])==='POST';
  $errors = [];

  if ($isPost){
    //Variablen aus POST lesen
    $recipient=$_POST['recipient']; $zip=$_POST['zip']; $city=$_POST['city']; $street=$_POST['street'];
    //schauen, ob Variablen gesetzt
    if (!((bool) $recipient)) $errors[]="Kein Empfänger angegeben";
    if (!((bool) $zip)) $errors[]="Kein ZIP angegeben";
    if (!((bool) $city)) $errors[]="Keine Stadt angegeben";
    if (!((bool) $street)) $errors[]="Keine Staße angegeben";
    //falls ja: Prüfen, ob gültig: Zeichenlängen
    if (count($errors)===0) {
      if (strlen($recipient)>255) $errors[]="Empfänger darf maximal 255 Zeichen haben";
      if (strlen($city)>50) $errors[]="Stadtname darf maximal 50 Zeichen lang sein";
      if (!strlen($zip)===5) $errors[]="Zip ist immer 5-stellig";
      if (strlen($street)>50) $errors[]="Straße & Hausnummer darf maximal 50 Zeichen lang sein";

      if (count($errors)===0) { //Registrierung erfolgreich?!? Neue Adresse anlegen, alte Adresse aus Datenbank entfernen falls nicht bei Delivery
        //Neue Adresse in Datenbank eintragen
        setUserAdress($recipient, $zip, $city, $street);
        //zurückleiten zur Seite von der man kam
        if (!$delivery)
          header("Location: ".$_SESSION['redirectTarget']);
        else
          header("Location: ".$baseURL."index.php/selectPayment");
        exit();
      }
    }
  //Standard User-Adresse, die User X angelegt hat laden
  } else {
    //To-Do
  }
  return $errors;
}
function loggingIn($baseURL):array { //$baseURL: Seite auf der Cookie gespeichert werden soll
  //Post-Request: Formular abgeschickt
  $isPost = strtoupper($_SERVER['REQUEST_METHOD'])==='POST';
  if (!isset($username)) $username=""; if (!isset($password)) $password="";
  $errors = [];

  if ($isPost){
    //Variablen aus POST lesen
    $username=$_POST['username']; $password=$_POST['password'];
    //schauen, ob Variablen gesetzt
    if (!((bool) $username))
      $errors[]="Kein Benutzername eingegeben";
    if (!((bool) $password))
      $errors[]="Kein Passwort eingegeben";
    //falls ja: Einlog-Versuch
    if (count($errors)===0) {
      $userData = getUserDataForUsername($username);
      if (count($userData) === 0)
        $errors[]="Benutzername existiert nicht";
      else { //Mit Passwort vergleichen
        if (password_verify($password, $userData['password'])) {
          //!!!Login erfolgreich!!!
          login($username, (int) $userData['id'], $baseURL);
          //zurückleiten zur Seite von der man kam
          header("Location: ".$_SESSION['redirectTarget']);
          exit();
        } else
          $errors[]="Passwort falsch";
      }
    }
  }
  return $errors;
}
function registering($baseURL):array { //$baseURL: Seite auf der Cookie gespeichert werden soll
  //Post-Request: Formular abgeschickt
  $isPost = strtoupper($_SERVER['REQUEST_METHOD'])==='POST';
  if (!isset($username)) $username=""; if (!isset($password)) $password="";
  $errors = [];

  if ($isPost){
    //Variablen aus POST lesen
    $username=$_POST['username']; $password=$_POST['password']; $mail=$_POST['mail'];
    //schauen, ob Variablen gesetzt
    if (!((bool) $username))
      $errors[]="Kein Benutzername eingegeben";
    if (!((bool) $password))
      $errors[]="Kein Passwort eingegeben";
    if (!((bool) $mail))
      $errors[]="Keine e-Mail-Adresse angegeben";
    //falls ja: Registrier-Versuch; Prüfen, ob Name schon vorhanden
    if (count($errors)===0) {
      if (strlen($password)<6)
        $errors[]="Passwort zu kurz (bitte mindestens 6 Buchstaben)";
      if (!ctype_alpha($username[0]))
        $errors[]="Benutzername muss mit Buchstaben beginnen!";
      if (strpos($username, " "))
        $errors[]="Benutzername darf keine Leerzeichen enthalten!";
      if (!strpos($mail, "@"))
        $errors[]="e-Mail Adresse ungültig";
      $userData = getUserDataForUsername($username);
      if (count($userData) > 0)
        $errors[]="Benutzername schon vergeben";
      if (count($errors)===0) { //Registrierung erfolgreich?!? Neuen Nutzer anlegen!!!
        //Neuen User in Datenbank eintragen
        createUser($username, password_hash($password, PASSWORD_DEFAULT), $mail);
        //Eingeloggt
        $id = (int) ((getUserDataForUsername($username))['id']);
        login($username, $id, $baseURL);
        //zurückleiten zur Seite von der man kam
        header("Location: ".$_SESSION['redirectTarget']);
        exit();
      }
    }
  }
  return $errors;
}
function login($username, $userid, $baseURL) {
  $_SESSION['userId'] = $userid;
  $_SESSION['userName'] = $username;
  moveCartProductsToAnotherUser((int)$_COOKIE['userId'], $userid);
  setcookie('userId', $userid, strtotime('+30 days'), $baseURL);
}
function isLoggedIn():bool{
  return isset($_SESSION['userId']);
}
