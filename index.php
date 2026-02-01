<?php
session_start();
$buyerLoggedIn = isset($_SESSION['Buyer_id']);
$sellerLoggedIn = isset($_SESSION['Seller_id']);

include('db_connection.php');

// Fetch items from the database
$sql = "SELECT * FROM Items LIMIT 8"; // Adjust the LIMIT as needed
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuyN Joy Shopping Center</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
  .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .card {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        .card-title, .card-text {
            text-align: center;
            text-decoration: none;
        }
  .card-link {
            text-decoration: none;
            color: inherit;
  }
  .btn-custom {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    transition: background-color 0.3s ease-in-out, color 0.3s ease-in-out;
  }
  .btn-custom:hover {
    background-color: #0C6DFD;
    color: #fff;
  }
  .custom-banner {
    background-image: url('images/back.webp');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
  }
  .icon-dark i {
    color: #343a40;
  }
  
.button-85 {
  padding: 0.6em 2em;
  border: none;
  outline: none;
  color: rgb(255, 255, 255);
  background: #111;
  cursor: pointer;
  position: relative;
  z-index: 0;
  border-radius: 10px;
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
}

.button-85:before {
  content: "";
  background: linear-gradient(
    45deg,
    #ff0000,
    #ff7300,
    #fffb00,
    #48ff00,
    #00ffd5,
    #002bff,
    #7a00ff,
    #ff00c8,
    #ff0000
  );
  position: absolute;
  top: -2px;
  left: -2px;
  background-size: 400%;
  z-index: -1;
  filter: blur(5px);
  -webkit-filter: blur(5px);
  width: calc(100% + 4px);
  height: calc(100% + 4px);
  animation: glowing-button-85 20s linear infinite;
  transition: opacity 0.3s ease-in-out;
  border-radius: 10px;
}

@keyframes glowing-button-85 {
  0% {
    background-position: 0 0;
  }
  50% {
    background-position: 400% 0;
  }
  100% {
    background-position: 0 0;
  }
}

.button-85:after {
  z-index: -1;
  content: "";
  position: absolute;
  width: 100%;
  height: 100%;
  background: #222;
  left: 0;
  top: 0;
  border-radius: 10px;
}
  
</style>
    
</head>
<body>
    
<?php
include('navbar.php');
?>

<!-- Banner Section -->
<div class="container mt-4">
  <div class="jumbotron bg-info text-white p-4 custom-banner">
       <div class="text-center"> <!-- Added text-center class to center align content -->
    <h1 class="display-4">Welcome to Online Shopping Center!</h1>
    <p class="lead">Your one-stop shop for all your needs. From electronics to fashion, we have it all.</p>
    <hr class="my-4">
    <p>Shop now and enjoy exclusive deals and offers!</p>
      <button class="button-85" role="button">Starting Shopping</button>
    </div>
  </div>
</div>


<!-- Featured Products Section -->
<div class="container mt-5">
    <h2 class="text-center mb-4">Products</h2>
    <div class="row">
        <?php
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '
                <div class="col-md-3 mb-4">
                  <div class="card h-100" onclick="location.href=\'item_details.php?id=' . $row["Item_id"] . '\';" style="cursor:pointer;">
                    <img src="'.$row["Images"].'" class="card-img-top" alt="'.$row["Item_Name"].'">
                    <div class="card-body">
                      <h5 class="card-title">'.$row["Item_Name"].'</h5>
                      <p style="color: red; font-weight: bold;" class="card-text">LKR '.$row["Prize"].'</p>
                    </div>
                  </div>
                </div>';
            }
        } else {
            echo '<p class="text-center">No products available.</p>';
        }
        $conn->close();
        ?>
    </div>
</div>



<!-- Categories Section -->
<div class="container mt-5">
  <h2 class="text-center mb-4">Categories</h2>
  <div class="row">
    <div class="col-md-4 mb-4">
      <a href="Electronic_Items.php" class="card-link">
        <div class="card">
          <img src="images/still-life-teenager-s-desk.webp" class="card-img-top" alt="Electronic Items">
          <div class="card-body">
            <h5 class="card-title text-center">Electronic Items</h5>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-4 mb-4">
      <a href="Babies_&_Toys.php" class="card-link">
        <div class="card">
          <img src="images/cute-baby-with-toys.webp" class="card-img-top" alt="Babies & Toys">
          <div class="card-body">
            <h5 class="card-title text-center">Babies & Toys</h5>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-4 mb-4">
      <a href="Fashion_Items.php" class="card-link">
        <div class="card">
          <img src="images/top-view-accessoires-travel-with-women-clothing-concept-white.webp" class="card-img-top" alt="Fashion Items">
          <div class="card-body">
            <h5 class="card-title text-center">Fashion Items</h5>
          </div>
        </div>
      </a>
    </div>
  </div>
</div>
<br>

<?php
include('footer.php');
?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
$conn->close();
?>
