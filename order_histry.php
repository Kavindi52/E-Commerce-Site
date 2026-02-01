<?php
session_start();
include('db_connection.php');

$buyerLoggedIn = isset($_SESSION['Buyer_id']);
if (!$buyerLoggedIn) {
    header('Location: loging.php');
    exit();
}

$buyerId = $_SESSION['Buyer_id'];

$sql = "SELECT * FROM Orders WHERE Buyer_id = '$buyerId'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
 <?php
include('navbar.php');
?>

<div class="container mt-5">
    <h1 class="mb-4">My Orders</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Order ID</th>
                
                <th>Total Price</th>
                <th>Delivery Address</th>
                <th>Phone Number</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $itemId = $row["Item_id"];
                    $itemSql = "SELECT Item_Name FROM Items WHERE Item_id = '$itemId'";
                    $itemResult = $conn->query($itemSql);
                    $itemRow = $itemResult->fetch_assoc();

                    echo '<tr>';
                    echo '<td>' . $row["order_id"] . '</td>';
                   
                    echo '<td>$' . $row["Total_Price"] . '</td>';
                    echo '<td>' . $row["Delivery_Address"] . '</td>';
                    echo '<td>' . $row["Phone"] . '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="6">No orders found.</td></tr>';
            }
            ?>
        </tbody>
    </table>
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
