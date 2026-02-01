<?php
session_start();
include('db_connection.php');

$buyerLoggedIn = isset($_SESSION['Buyer_id']);
$buyerId = $buyerLoggedIn ? $_SESSION['Buyer_id'] : null;

if (!$buyerLoggedIn) {
    header("Location: loging.php");
    exit;
}

// Fetch cart items with extended price (Price * Quantity)
$sql = "SELECT Items.Item_id, Items.Item_Name, Items.Description, Items.Prize, Items.Images, Cart.Quantity, Items.Prize * Cart.Quantity AS TotalPrice
        FROM Cart 
        INNER JOIN Items ON Cart.Item_id = Items.Item_id 
        WHERE Cart.Buyer_id = '$buyerId'";

$result = $conn->query($sql);

$cartItems = [];
$totalPrice = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
        $totalPrice += $row['TotalPrice']; // Accumulate TotalPrice for each item
    }
}

// Store total price in session
$_SESSION['totalPrice'] = $totalPrice;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
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
    </style>
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h1 class="mb-4">Shopping Cart</h1>
    <?php if (!empty($cartItems)): ?>
        <div class="row">
            <?php foreach ($cartItems as $item): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?php echo $item['Images']; ?>" class="card-img-top" alt="Item Image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $item['Item_Name']; ?></h5>
                            <p class="card-text"><strong>Price: LKR </strong><?php echo $item['Prize']; ?></p>
                            <p class="card-text"><strong>Quantity: </strong><?php echo $item['Quantity']; ?></p>
                            <button class="btn btn-danger" onclick="removeFromCart(<?php echo $item['Item_id']; ?>)">Remove</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="row">
            <div class="col-12 text-end">
                <h3>Total Price: LKR <?php echo $totalPrice; ?></h3>
                <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
            </div>
        </div>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</div>

<?php include('footer.php'); ?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function removeFromCart(itemId) {
        $.ajax({
            url: 'remove_from_cart.php',
            type: 'POST',
            data: {
                item_id: itemId
            },
            success: function(response) {
                location.reload();
            },
            error: function() {
                alert('Failed to remove item from cart.');
            }
        });
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
