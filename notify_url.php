<?php
session_start();
include('db_connection.php');

$buyerLoggedIn = isset($_SESSION['Buyer_id']);
$buyerId = $buyerLoggedIn ? $_SESSION['Buyer_id'] : null;

// Read the notification data sent by PayHere
$merchant_id = $_POST['merchant_id'];
$order_id = $_POST['order_id'];
$payment_id = $_POST['payment_id'];
$payhere_amount = $_POST['payhere_amount'];
$payhere_currency = $_POST['payhere_currency'];
$status_code = $_POST['status_code'];
$md5sig = $_POST['md5sig'];

// Generate the local signature
$merchant_secret = 'NjM0MDE5MjQxMTQ5ODY1MzEzNzE4MzYzMDY4OTYzODk2MDY1MDcx';
$local_md5sig = strtoupper(
    md5(
        $merchant_id .
        $order_id .
        $payhere_amount .
        $payhere_currency .
        $status_code .
        strtoupper(md5($merchant_secret))
    )
);

// Verify the signature
if ($local_md5sig === $md5sig && $status_code == 2) {
    // Fetch order details from the session
    $buyerId = $_SESSION['Buyer_id'];
    $deliveryAddress = $_SESSION['deliveryAddress'];
    $recipientName = $_SESSION['recipientName'];
    $phoneNumber = $_SESSION['phoneNumber'];
    $paymentMethod = 'card'; // Since it's PayHere, we set the payment method to card
    $totalPrice = $payhere_amount; // Use the amount from the notification

    // Debugging: Output session data
    echo "Session Data: <br>";
    echo "Buyer ID: $buyerId <br>";
    echo "Delivery Address: $deliveryAddress <br>";
    echo "Recipient Name: $recipientName <br>";
    echo "Phone Number: $phoneNumber <br>";
    echo "Total Price: $totalPrice <br>";

    // Save order details in the database
    $sql = "INSERT INTO Orders (Buyer_id, Recipient_Name, Delivery_Address, Phone, Payment_Method, Total_Price) 
            VALUES ('$buyerId', '$recipientName', '$deliveryAddress', '$phoneNumber', '$paymentMethod', '$totalPrice')";
    
    if ($conn->query($sql) === TRUE) {
        // Get the last inserted order ID
        $orderId = $conn->insert_id;

        // Delete cart items related to the current buyer ID
        $deleteCartSql = "DELETE FROM Cart WHERE Buyer_id = '$buyerId'";
        if ($conn->query($deleteCartSql) === TRUE) {
            // Unset totalPrice session variable after order is placed
            unset($_SESSION['totalPrice']);
            // Redirect to a confirmation page
            header("Location: confirmation.php?order_id=$orderId"); // Redirect to confirmation page with order ID
            exit;
        } else {
            echo "Error deleting cart items: " . $conn->error;
        }
    } else {
        echo "Error placing order: " . $conn->error . "<br>";
        echo "SQL: " . $sql . "<br>";
    }
} else {
    echo "Invalid signature or status code";
}
?>
