<!DOCTYPE html>
<html lang="de">
  <?php include __DIR__.'/header.php' ?>

  <section class="container" id="loginForm">
  <!-- When the user fills out the form below and clicks the submit button,
       the form data is sent with the HTTP POST method. $_POST["name"] for the variables in PHP -->
  <form action="index.php/login" method="POST">
    <div class="card">
      <div class="card-header">Login</div>
      <div class="card-body">
        <?php if(count($errors)>0):?>
          <div class="alert alert-danger" role="alert">
            <?php foreach($errors as $errorMessage):?>
              <p><?= $errorMessage ?></p>
            <?php endforeach?>
          </div>
        <?php endif;?>
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" name="username" id="username" class="form-control">
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" name="password" id="password" class="form-control">
        </div>
      </div>
      <div class="card-footer">
        <button class="btn btn-success" type="submit">Login</button>
      </div>
    </div>
  </form>

  <script src="assets/js/bootstrap.bundle.js"></script>
 </body>
</html>
