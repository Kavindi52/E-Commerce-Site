<?php
session_start();
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_SESSION['Buyer_email']; // Ensure you have a session variable for the username
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO messages (user, message) VALUES (?, ?)");
    $stmt->bind_param('ss', $user, $message);
    $stmt->execute();
}
?>
