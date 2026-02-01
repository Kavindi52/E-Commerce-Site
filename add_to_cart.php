<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['Buyer_id'])) {
    header('Location: loging.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['item_id']) && isset($_POST['quantity'])) {
    $itemId = $_POST['item_id'];
    $quantity = $_POST['quantity'];
    $buyerId = $_SESSION['Buyer_id'];

    // Check if the item is already in the cart for the current buyer
    $checkSql = "SELECT * FROM Cart WHERE Buyer_id = '$buyerId' AND Item_id = '$itemId'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        // Update the quantity if the item is already in the cart
        $updateSql = "UPDATE Cart SET Quantity = Quantity + $quantity WHERE Buyer_id = '$buyerId' AND Item_id = '$itemId'";
        if ($conn->query($updateSql) === TRUE) {
            header('Location: cart.php');
        } else {
            echo "Error updating cart: " . $conn->error;
        }
    } else {
        // Insert the item into the cart with the specified quantity
        $sql = "INSERT INTO Cart (Buyer_id, Item_id, Quantity) VALUES ('$buyerId', '$itemId', '$quantity')";

        if ($conn->query($sql) === TRUE) {
            header('Location: cart.php');
        } else {
            echo "Error adding to cart: " . $conn->error;
        }
    }
}
?>
