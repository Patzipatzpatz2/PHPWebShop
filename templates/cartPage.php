<!DOCTYPE html>
<html lang="de">
   <?php include __DIR__.'/header.php' ?>

   <section class="container" id="cartItems">
     <div class="row">
       <h2>Warenkorb</h2>
     </div>
     <div class="row cartItemHeader">
       <div class="col-12 text-right">Preis</div>
     </div>
     <?php foreach ($cartItems as $cartItem):?>
       <div class="row cartItem">
          <?php include __DIR__.'/cartItem.php';?>
       </div>
     <?php endforeach;?>
     <div class="row">
       <div class="col-12 text-right">
         Summe (<?= $countCartItems ?> Artikel): <span class="price"><?= number_format($cartSum/100, 2, ",", " ")?> €
       </div>
     </div>
     <div class="row">
       <a href="index.php/setAdress" class="btn btn-primary col-12">Zur Kasse gehen</a>
     </div>
   </section>

  <script src="assets/js/bootstrap.bundle.js"></script>
 </body>
</html>
