<nav class="navbar navbar-light bg-light py-0">
  <div class="container-fluid">
    <div class="d-flex justify-content-end w-100">
      <a class="nav-link text-dark px-2 small" href="seller_loging.php"><small>Become a Seller</small></a>
      <a class="nav-link text-dark px-2 small" href="#"><small>Help & Support</small></a>
    </div>
  </div>
</nav>

<nav class="navbar navbar-expand-lg navbar-light bg-primary px-3">
  <a class="navbar-brand text-white" href="#">BuyN Joy Shopping Center</a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
    <div class="mx-auto">
      <div class="navbar-nav">
        <a class="nav-item nav-link active text-white" href="index.php">Home <span class="sr-only"></span></a>
        <a class="nav-item nav-link text-white" href="about.php">About</a>
        <a class="nav-item nav-link text-white" href="contact.php">Contact Info</a>
        <div class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Items Category
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="Electronic_Items.php">Electronic Items</a></li>
            <li><a class="dropdown-item" href="Babies_&_Toys.php">Babies & Toys</a></li>
            <li><a class="dropdown-item" href="Fashion_Items.php">Fashion Items</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">View All Items</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="d-flex align-items-center">
      <a href="#" class="icon-dark me-3"><i class="fas fa-bell text-white"></i></a>
      <a href="cart.php" class="icon-dark me-3"><i class="fas fa-shopping-cart text-white"></i></a>
      <?php if (!$buyerLoggedIn && !$sellerLoggedIn): ?>
        <a href="loging.php" class="btn btn-outline-light me-2">
          <i class="fas fa-sign-in-alt"></i> Login
        </a>
        <a href="register.php" class="btn btn-outline-light">
          Signup
        </a>
      <?php else: ?>
        <a href="order_histry.php" class="icon-dark me-3"><i class="fas fa-user-alt text-white"></i></a>
        
        <a href="logout.php" class="btn btn-outline-light me-2">
          <i class="fas fa-sign-out-alt"></i> Log Out
        </a>
        
      <?php endif; ?>
    </div>
  </div>
</nav>
