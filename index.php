<?php
session_start();
error_reporting(-1);
ini_set('display_errors','On');

define('CONFIG_DIR', __DIR__.'/config');
require_once __DIR__.'/includes.php'; //includes beeinhaltet eine Einbindung aller Funktionen

//bestimmen auf welche Seite man geleitet wird
$url = $_SERVER['REQUEST_URI']; //aktuelle Adresse, z.B. "localhost/shop/index.php/cart" oder "localhost/shop/index.php"
$indexPHPPosition = strpos($url, 'index.php');
$part = substr($url, $indexPHPPosition); //part: alles vor "index.php" weg, z.B. "index.php/cart" oder "index.php"
$baseURL = substr($url, 0, $indexPHPPosition); //baseURL: alles ab "index.php" weg, z.B. "localhost/shop/"
$route = str_replace('index.php','',$part); //route: part ohne "index.php", z.B. "/cart" oder ""

$userId = getCurrentUserId();
setcookie('userId', $userId, strtotime('+30 days'), $baseURL);
$countCartItems = countProductsInCart($userId); //number of cartItems

//auf Startseite '/shop/index.php' bzw. '/shop/'
if (!$route) {
  $_SESSION['redirectTarget'] = $_SERVER['REQUEST_URI'];
  $products = getAllProducts();
  require __DIR__.'/templates/main.php';
  exit(); //sowas wie break; code danach wird nicht mehr ausgeführt
}
//Click auf "add to cart" leitet zu '/shop/index/cart/add/<id>' id=productID
if (strpos($route, '/cart/add')!==false) {
  $routeParts = explode("/",$route);
  $productId = (int) $routeParts[3];
  addProductToCart($userId, $productId); //Funktion in function/cart.php, führt SQL aus
  header("Location: ".$_SESSION['redirectTarget']);
  exit();
}
//Click auf "remove 1 item" or so leitet zu '/shop/index/cart/remove/<id>' id=productID
if (strpos($route, '/cart/remove')!==false) {
  $routeParts = explode("/",$route);
  $productId = (int) $routeParts[3];
  var_dump(removeOneOfProductFromCart($userId, $productId)); //Funktion in function/cart.php, führt SQL aus
  header("Location: ".$_SESSION['redirectTarget']);
  exit();
}
//Click auf "cart" in navbar leitet zu '/shop/index/cart/'
if (strpos($route, '/cart')!==false) {
  $_SESSION['redirectTarget'] = $_SERVER['REQUEST_URI'];
  $cartItems = getCartItemsForUserId($userId);
  $cartSum = getCartSumForUserId($userId);
  require __DIR__.'/templates/cartPage.php';
  exit();
}
//Click auf "login"
if (strpos($route, '/login')!==false) {
  $errors = loggingIn($baseURL);
  require __DIR__.'/templates/login.php';
  exit();
}
//Click auf "register"
if (strpos($route, '/register')!==false) {
  $errors = registering($baseURL);
  require __DIR__.'/templates/register.php';
  exit();
}
//Click auf "logout"
if (strpos($route, '/logout')!==false) {
  session_regenerate_id(true);
  session_destroy();
  //zurückleiten zur Seite von der man kam
  header("Location: ".$_SESSION['redirectTarget']);
  $userId = random_int(0, time());
  setcookie('userId', $userId, strtotime('+30 days'), $baseURL);
  exit();
}
//Click auf "setAdress"
if (strpos($route, '/setAdress')!==false) {
  if (!isLoggedIn()){
    header("Location: ".$baseURL."index.php/login");
    exit();
  } else {
    $adress = getAdressForUserId($_SESSION['userId']);
    $errors = userAdressSetting($baseURL, $delivery);
    require __DIR__.'/templates/setAdress.php';
  }
  exit();
}
//Nach Adress-Bestätigung
if (strpos($route, '/selectPayment')!==false) {
  if (!isLoggedIn()){
    $_SESSION['redirectTarget'] = $baseURL."index.php/checkout";
    header("Location: ".$baseURL."index.php/login");
    exit();
  } else {
    require __DIR__.'/templates/selectPayment.php';
  }
  exit();
}

header("Location: /shop/index.php"); //wenn auf einer nicht oben spezifierten Sub-Seite, z.B. "localhost/shop/index.php/hugaga" redirect zur startseite
