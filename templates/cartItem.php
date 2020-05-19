<!-- 3-7-2 "Breite-Einheiten", bootstrap hat 12 Spalten, die über den ganzen Bildschirm verteilt werden
in 1. 4 sind die Bilder und in den 8 die Eigenschaften als Text untereinander-->
<div class="col-3">
  <img class="productPicture" src="https://placekitten.com/286/170">
</div>
<div class="col-7">
  <div> <?= $cartItem['title']?> </div>
  <div> <?= $cartItem['description']?> </div><br>
  <a href="index.php/cart/add/<?= $cartItem['id']?>" class="btn btn-success">Add 1</a>
  <a href="index.php/cart/remove/<?= $cartItem['id']?>" class="btn btn-danger">Remove 1</a>
</div>
<div class="col-2 text-right"> <!-- text-right: auf der rechten Seite des Bldschirms -->
  <span class="price">
    <div> <?= number_format($cartItem['price']/100, 2, ",", " ")?> €</div>
    <div> x<?= $cartItem['quantity']?> </div>
  </span>
</div>
