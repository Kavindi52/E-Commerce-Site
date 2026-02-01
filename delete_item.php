<?php
session_start();

if (!isset($_SESSION['seller_id'])) {
    header('Location: seller_loging.php');
    exit();
}

include('db_connection.php');

$itemId = $_GET['id'];
$sellerId = $_SESSION['seller_id'];

$sql = "DELETE FROM Items WHERE Item_id='$itemId' AND seller_id='$sellerId'";

if ($conn->query($sql) === TRUE) {
    header('Location: editItem.php');
} else {
    echo "Error deleting record: " . $conn->error;
}

$conn->close();
?>
