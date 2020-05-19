<nav class="navbar navbar-expand-lg navbar-light bg-light">
 <div class="container">
  <a class="navbar-brand" href="index.php">My shop</a>
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <?php if (isset($_SESSION['userId'])):?>
        Hallo, <?= $_SESSION['userName'] ?>!
        <a href="index.php/setAdress">Lieferadresse</a>
        <a href="index.php/logout">Logout</a>
      <?php else:?>
        <a href="index.php/register">Registrieren</a>
        <a href="index.php/login">Login</a>
      <?php endif;?>
      <a href="index.php/cart">Warenkorb (<?= $countCartItems ?>)</a>
    </li>
  </ul>
 </div>
</nav>
