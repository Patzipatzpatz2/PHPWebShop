<!DOCTYPE html>
<html lang="de">
   <?php include __DIR__.'/header.php' ?>

   <section class="container" id="products">
    <div class="container">
     <div class="row">
       <?php foreach ($products as $product):?>
         <div class="col"> <?php include 'card.php'?> </div>
       <?php endforeach; ?>
     </div>
   </div>

  <script src="assets/js/bootstrap.bundle.js"></script>
 </body>
</html>
