<?php
function getAllProducts() {
  //Produkte aus Datenbank lesen
  $sql = "SELECT id, title, description, price FROM products";
  $result = getDB()->query($sql);
  //Es gibt keine -> leeres Array
  if (!$result) {
    return [];
  }
  //Es gibt welche
  $products = [];
  while ($row = $result->fetch()) {
    $products[]=$row;
  }
  return $products;
}
