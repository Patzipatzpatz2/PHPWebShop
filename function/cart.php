<?php
function addProductToCart(int $userId, int $productId) {
  $sql = "INSERT INTO cart
          SET quantity=1, user_id=:userId, product_id=:productId
          ON DUPLICATE KEY UPDATE quantity = quantity+1";
  $statement = getDB()->prepare($sql); //statement mit Variablen in Datenbank zwischengespeichert
  $statement->execute([ //variablen einsetzen
    ':userId' => $userId,
    ':productId' => $productId
  ]);
}
function removeOneOfProductFromCart(int $userId, int $productId):int {
  $sql = "UPDATE cart SET quantity=quantity-1 WHERE user_id=:userId AND product_id=:productId;
          DELETE FROM cart WHERE quantity=0";
  $statement = getDB()->prepare($sql);
  return $statement->execute([':userId' => $userId, ':productId' => $productId]); //#betroffene Reihen, =1 im Erfolgsfall..
}
function countProductsInCart(int $userId) {
  $sql = "SELECT SUM(quantity) FROM cart WHERE user_id =".$userId;
  $cartResults = getDb()->query($sql);
  if ($cartResults===false)
    //var_dump(printDBErrorMessage());
    return 0;
  $cartItems = $cartResults->fetchColumn();
  if (!$cartItems)
    return 0;
  else
    return $cartItems;
}
function getCartItemsForUserId(int $userId):array {
  $sql = "SELECT product_id AS id, title, description, price, quantity
          FROM cart JOIN products ON (cart.product_id = products.id)
          WHERE user_id = ".$userId;
  $results = getDb()->query($sql);
  if ($results===false) {
    return[];
  }
  $found = [];
  while ($row = $results->fetch()){
    $found[]=$row;
  }
  return $found;
}
function getCartSumForUserId(int $userId): int {
  $sql = "SELECT SUM(price * quantity)
          FROM cart JOIN products ON (cart.product_id = products.id)
          WHERE user_id = ".$userId;
  $result = getDb()->query($sql);
  if ($result===false) {
    return 0;
  }
  return (int) $result->fetchColumn(); //int convert, da NULL falls keine Daten
}
function moveCartProductsToAnotherUser(int $sourceUserId, int $targetUserId):int {
  $sql = "UPDATE cart SET user_id=:targetUser WHERE user_id=:sourceUser";
  $statement = getDB()->prepare($sql);
  if ($statement===false)
    return 0;
  return $statement->execute([':sourceUser' => $sourceUserId, ':targetUser' => $targetUserId]); //#betroffene Zeilen
}
function deleteCartProductsFromUser(int $userId):int {
  $sql = "DELETE FROM cart WHERE user_id=:sourceUser";
  $statement = getDB()->prepare($sql);
  if ($statement===false)
    return 0;
  return $statement->execute([':sourceUser' => $userId]); //#betroffene Zeilen zurückgeben
}
