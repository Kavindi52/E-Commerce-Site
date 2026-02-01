<?php
session_start();

if (!isset($_SESSION['seller_id'])) {
    header('Location: seller_loging.php'); // Redirect to login page if seller_id is not set
    exit();
}

// Assuming you have stored first_name and last_name in session
$fullName = isset( $_SESSION['seller_fname']) && isset($_SESSION['seller_lname']) ?  $_SESSION['seller_fname'] . ' ' . $_SESSION['seller_lname'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Interface</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/seller_dashboard.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
</head>
<body>
    
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<nav class="navbar navbar-expand-lg navbar-light bg-primary px-3">
  <a class="navbar-brand" href="#">Online Shopping Center Seller Interface</a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
    <div class="mx-auto">
      <div class="navbar-nav">
        <a class="nav-item nav-link active" href="index.php">Home <span class="sr-only"></span></a>
        <a class="nav-item nav-link" href="about.php">About</a>
        <a class="nav-item nav-link" href="contact.php">Contact Info</a>
        <div class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
        <?php if (!empty($fullName)) : ?>
            <div class="text-light mx-3">Welcome, <?php echo $fullName; ?></div>
        <?php endif; ?>
    </div>
  </div>
</nav>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3 mb-4">
           <a href="additem.php"> <div class="colored-square square-red">Add Item</div> </a>
        </div>
        <div class="col-md-3 mb-4">
        <a href="check_orders.php">  <div class="colored-square square-blue">Check Orders</div> </a>
        </div>
        <div class="col-md-3 mb-4">
       <a href="editItem.php">   <div class="colored-square square-green">Edit Items</div> </a>
        </div>
        <div class="col-md-3 mb-4">
        <a href="">   <div class="colored-square square-yellow">Process Orders</div></a>
        </div>
        <div class="col-md-3 mb-4">
        <a href="">  <div class="colored-square square-orange">Tracking Orders</div></a>
        </div>
        <div class="col-md-3 mb-4">
        <a href="">   <div class="colored-square square-purple">Total Collection</div></a>
        </div>
        <div class="col-md-3 mb-4">
        <a href="logout.php">   <div class="colored-square square-black">LogOut As Seller</div></a>
        </div>
        <div class="col-md-3 mb-4">
        <a href="index.php">   <div class="colored-square square-black">Home</div></a>
        </div>
    </div>
</div>

<?php
include('footer.php');
?>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<style>
  .icon-dark i {
    color: #343a40;
  }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
