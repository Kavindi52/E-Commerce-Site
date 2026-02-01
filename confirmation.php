<?php
session_start();
$buyerLoggedIn = isset($_SESSION['Buyer_id']);
$buyerId = $buyerLoggedIn ? $_SESSION['Buyer_id'] : null;

if (!$buyerLoggedIn) {
    header("Location: loging.php");
    exit;
}

// Include database connection
include('db_connection.php');

// Fetch the latest order details for the logged-in user
$buyerId = $_SESSION['Buyer_id'];
$sql = "SELECT * FROM Orders WHERE Buyer_id = '$buyerId' ORDER BY order_id DESC LIMIT 1";
$result = $conn->query($sql);

$orderDetails = [];
if ($result->num_rows > 0) {
    $orderDetails = $result->fetch_assoc();
} else {
    echo "No recent order found.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
 <?php
include('navbar.php');
?>

<div class="container mt-5">
    <h1 class="mb-4">Order Confirmation</h1>
    <h2>Thank you for your purchase!</h2>
    <p>Your order has been placed successfully. Here are the details of your order:</p>
    
    <table class="table">
        <tr>
            <th>Order ID:</th>
            <td><?php echo $orderDetails['order_id']; ?></td>
        </tr>
        <tr>
            <th>Recipient Name:</th>
            <td><?php echo $orderDetails['Recipient_Name']; ?></td>
        </tr>
        <tr>
            <th>Delivery Address:</th>
            <td><?php echo $orderDetails['Delivery_Address']; ?></td>
        </tr>
        <tr>
            <th>Phone Number:</th>
            <td><?php echo $orderDetails['Phone']; ?></td>
        </tr>
        <tr>
            <th>Payment Method:</th>
            <td><?php echo ucfirst($orderDetails['Payment_Method']); ?></td>
        </tr>
        <tr>
            <th>Total Price:</th>
            <td>$<?php echo $orderDetails['Total_Price']; ?></td>
        </tr>
    </table>
    
    <p>You will receive an email confirmation shortly.</p>
    <a href="index.php" class="btn btn-primary">Continue Shopping</a>
</div>

<?php
include('footer.php');
?>
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
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
