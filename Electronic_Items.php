<?php
session_start();
include('db_connection.php');

$buyerLoggedIn = isset($_SESSION['Buyer_id']);

// Handle the Add to Cart functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['item_id'])) {
    if ($buyerLoggedIn) {
        $itemId = $_POST['item_id'];
        $buyerId = $_SESSION['Buyer_id'];

        $sql = "INSERT INTO Cart (Buyer_id, Item_id) VALUES ('$buyerId', '$itemId')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $conn->error]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electronic Items</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .icon-dark i {
            color: #343a40;
        }
        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .card {
            transition: transform 0.2s ease-in-out;
        }
        .card:hover {
            transform: translateY(-10px);
        }
        .price {
            font-size: 1.25rem;
            color: #ED0002;
            transition: color 0.2s, background-color 0.2s;
        }
        
    </style>
</head>
<body>
 <?php
include('navbar.php');
?>
<div class="container mt-5">
    <h1 class="mb-4">Electronic Items</h1>
    <div class="row">
      <?php
      $sql = "SELECT * FROM Items WHERE Category = 'Electronic Items'";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
              echo '<div class="col-md-4 mb-4">';
              echo '<div class="card h-100" onclick="location.href=\'item_details.php?id=' . $row["Item_id"] . '\';" style="cursor:pointer;">';
              echo '<img src="' . $row["Images"] . '" class="card-img-top" alt="Item Image">';
              echo '<div class="card-body">';
              echo '<h5 class="card-title">' . $row["Item_Name"] . '</h5>';
              echo '<p class="card-text price">LKR ' . $row["Prize"] . '</p>';
              echo '</div>';
              echo '</div>';
              echo '</div>';
          }
      } else {
          echo '<p>No electronic items found.</p>';
      }

      $conn->close();
      ?>
    </div>
  </div>
<?php
include('footer.php');
?>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
