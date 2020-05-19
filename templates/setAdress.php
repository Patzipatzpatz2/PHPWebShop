<!DOCTYPE html>
<html lang="de">
  <?php include __DIR__.'/header.php' ?>

  <section class="container" id="loginForm">
  <!-- When the user fills out the form below and clicks the submit button,
       the form data is sent with the HTTP POST method. $_POST["name"] for the variables in PHP -->
  <form action="index.php/setAdress" method="POST">
    <div class="card">
      <div class="card-header">Lieferadresse</div>
      <div class="card-body">
        <?php if(count($errors)>0):?>
          <div class="alert alert-danger" role="alert">
            <?php foreach($errors as $errorMessage):?>
              <p><?= $errorMessage ?></p>
            <?php endforeach?>
          </div>
        <?php endif;?>
        <div class="form-group">
          <label for="recipient">Empfänger</label>
          <input type="text" name="recipient" id="recipient" value="<?=$adress['inhabitant']?>" class="form-control">
        </div>
        <div class="form-group">
          <label for="zip">ZIP Code</label>
          <input type="text" name="zip" id="zip" value="<?=$adress['zip_code']?>" class="form-control">
        </div>
        <div class="form-group">
          <label for="city">Stadt</label>
          <input type="text" name="city" id="city" value="<?=$adress['city']?>" class="form-control">
        </div>
        <div class="form-group">
          <label for="street">Straße & Hausnummer</label>
          <input type="text" name="street" id="street" value="<?=$adress['street']?>" class="form-control">
        </div>
        <div class="card-footer">
          <button class="btn btn-success" type="submit">Ok</button>
        </div>
      </div>
    </form>
    <script src="assets/js/bootstrap.bundle.js"></script>
   </body>
  </html>
