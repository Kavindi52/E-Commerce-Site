<?php
session_start();
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $itemId = $_POST['item_id'];
    $buyerId = $_SESSION['Buyer_id'];

    $sql = "DELETE FROM Cart WHERE Buyer_id = '$buyerId' AND Item_id = '$itemId'";
    if ($conn->query($sql) === TRUE) {
        echo "Item removed successfully";
    } else {
        echo "Error removing item: " . $conn->error;
    }

    $conn->close();
}
?>
