<div class="card">
  <div class="card-title"><?= $product['title']?></div>
  <img src="https://placekitten.com/286/170" class="card-img-top" alt="...">
  <div class="card-body">
    <?= $product['description'] ?>
    <hr>
    <?= $product['price'] ?>
  </div>
  <div class="card-footer">
    <a href="" class="btn btn-primary">Details</a>
    <a href="index.php/cart/add/<?= $product['id']?>" class="btn btn-success">In den Warenkorb</a>
  </div>
</div>
