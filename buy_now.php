<?php
session_start();
include('db_connection.php');

if (!isset($_GET['id']) || !isset($_GET['quantity'])) {
    header('Location: index.php');
    exit();
}

$itemId = $_GET['id'];
$itemQuantity = $_GET['quantity'];

$sql = "SELECT Prize FROM Items WHERE Item_id = '$itemId'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header('Location: index.php');
    exit();
}

$item = $result->fetch_assoc();

// Calculate total price based on item quantity
$totalPrice = $item['Prize'] * $itemQuantity;

// Store total price and item quantity in session
$_SESSION['totalPrice'] = $totalPrice;
$_SESSION['itemQuantity'] = $itemQuantity;

// Redirect to the checkout page
header('Location: checkout.php');
exit();
?>
