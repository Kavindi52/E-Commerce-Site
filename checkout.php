<?php
session_start();
include('db_connection.php');

$buyerLoggedIn = isset($_SESSION['Buyer_id']);
$buyerId = $buyerLoggedIn ? $_SESSION['Buyer_id'] : null;

if (!$buyerLoggedIn) {
    header("Location: loging.php");
    exit;
}

// Fetch buyer details
$sql = "SELECT Buyer_fname, Buyer_lname, Buyer_address, Buyer_pnum, Buyer_email FROM Buyer_Registration WHERE Buyer_id = '$buyerId'";
$result = $conn->query($sql);

$buyerDetails = [];
if ($result->num_rows > 0) {
    $buyerDetails = $result->fetch_assoc();
    $recipientName = $buyerDetails['Buyer_fname'] . ' ' . $buyerDetails['Buyer_lname'];
}

// Fetch cart items with item names and quantities
$cartItems = [];
$totalPrice = 0;
$sql = "SELECT Items.Item_id, Items.Item_Name, Cart.Quantity, Items.Prize 
        FROM Cart 
        JOIN Items ON Cart.Item_id = Items.Item_id 
        WHERE Cart.Buyer_id = '$buyerId'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
        $totalPrice += $row['Prize'] * $row['Quantity'];
    }
}

// Store total price in session
$_SESSION['totalPrice'] = $totalPrice;

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['paymentMethod'])) {
    // Sanitize and validate form inputs
    $deliveryAddress = htmlspecialchars($_POST['deliveryAddress']);
    $recipientName = htmlspecialchars($_POST['recipientName']);
    $phoneNumber = htmlspecialchars($_POST['phoneNumber']);
    $paymentMethod = htmlspecialchars($_POST['paymentMethod']);
    
    // Store order details in session for PayHere notification handling
    $_SESSION['deliveryAddress'] = $deliveryAddress;
    $_SESSION['recipientName'] = $recipientName;
    $_SESSION['phoneNumber'] = $phoneNumber;
    
    if ($paymentMethod === 'cash') {
        // Save order details in the database
        $sql = "INSERT INTO Orders (Buyer_id, Recipient_Name, Delivery_Address, Phone, Payment_Method, Total_Price) 
                VALUES ('$buyerId', '$recipientName', '$deliveryAddress', '$phoneNumber', '$paymentMethod', '$totalPrice')";
        
        if ($conn->query($sql) === TRUE) {
            // Get the last inserted order ID
            $orderId = $conn->insert_id;

            // Insert each item into Order_Items table
            foreach ($cartItems as $item) {
                $itemId = $item['Item_id'];
                $quantity = $item['Quantity'];
                $itemPrice = $item['Prize'];
                
                $insertItemSql = "INSERT INTO Order_Items (Order_id, Item_id, Quantity, Item_Price) 
                                  VALUES ('$orderId', '$itemId', '$quantity', '$itemPrice')";
                
                if (!$conn->query($insertItemSql)) {
                    echo "Error inserting item into Order_Items table: " . $conn->error;
                }
            }

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
            echo "Error placing order: " . $conn->error;
        }
    }
}

$merchant_id = '1226249';
$merchant_secret = 'MTA3NDcwODI1NjIxMzkzNTY4NzIxNzk2NTY1MTIwNDA2Mzk5NTMxNA==';
$order_id = uniqid();
$currency = 'LKR';

$hash = strtoupper(
    md5(
        $merchant_id . 
        $order_id . 
        number_format($totalPrice, 2, '.', '') . 
        $currency .  
        strtoupper(md5($merchant_secret)) 
    ) 
);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>
    <style>
        .icon-dark i {
            color: #343a40;
        }

        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .total-price {
            position: absolute;
            bottom: 20px;
            left: 0;
            width: 100%;
            text-align: center;
        }
    </style>
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <form id="checkoutForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <div class="mb-3">
                    <label for="deliveryAddress" class="form-label">Delivery Address</label>
                    <textarea class="form-control" id="deliveryAddress" name="deliveryAddress" rows="3" required><?php echo isset($buyerDetails['Buyer_address']) ? $buyerDetails['Buyer_address'] : ''; ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="recipientName" class="form-label">Recipient Name</label>
                    <input type="text" class="form-control" id="recipientName" name="recipientName" value="<?php echo isset($recipientName) ? $recipientName : ''; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="phoneNumber" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" value="<?php echo isset($buyerDetails['Buyer_pnum']) ? $buyerDetails['Buyer_pnum'] : ''; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="paymentMethod" class="form-label">Payment Method</label>
                    <select class="form-select" id="paymentMethod" name="paymentMethod" required>
                        <option>Select</option>
                        <option value="card">Credit/Debit Card</option>
                        <option value="cash">Cash on Delivery</option>
                    </select>
                </div>
                <div id="codButton" style="display:none;">
                    <button type="button" class="btn btn-primary" id="codSubmit">Cash On Delivery</button>
                </div>
                <div id="payhereButton" style="display:none;">
                    <button class="btn btn-primary" type="button" id="payhere-payment">PayHere Pay</button>
                </div>
            </form><br>
        </div>
        
        <div class="col-md-6 position-relative">
    <table class="table table-bordered mb-4">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Price (LKR)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cartItems as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['Item_Name']); ?></td>
                    <td><?php echo htmlspecialchars($item['Quantity']); ?></td>
                    <td><?php echo htmlspecialchars($item['Prize'] * $item['Quantity']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <h2 class="total-price">Total Price: LKR <?php echo $totalPrice; ?></h2>
</div>

    </div>
</div>

<script type="text/javascript">
    document.getElementById('paymentMethod').addEventListener('change', function() {
        var selectedMethod = this.value;
        if (selectedMethod === 'cash') {
            document.getElementById('codButton').style.display = 'block';
            document.getElementById('payhereButton').style.display = 'none';
        } else {
            document.getElementById('codButton').style.display = 'none';
            document.getElementById('payhereButton').style.display = 'block';
        }
    });

    document.getElementById('codSubmit').addEventListener('click', function() {
        document.getElementById('checkoutForm').submit();
    });

    // Payment completed. It can be a successful failure.
    payhere.onCompleted = function onCompleted(orderId) {
        console.log("Payment completed. OrderID:" + orderId);
        // Note: validate the payment and show success or failure page to the customer
        window.location.href = "confirmation.php?order_id=" + orderId; // Redirect to confirmation page
    };

    // Payment window closed
    payhere.onDismissed = function onDismissed() {
        // Note: Prompt user to pay again or show an error page
        console.log("Payment dismissed");
    };

    // Error occurred
    payhere.onError = function onError(error) {
        // Note: show an error page
        console.log("Error:"  + error);
    };

    // Put the payment variables here
    var payment = {
        "sandbox": true,
        "merchant_id": "<?php echo $merchant_id; ?>",    // Replace your Merchant ID
        "return_url": "https://luthiradissanayeka.com/checkout.php",     // Important
        "cancel_url": "https://luthiradissanayeka.com/checkout.php",     // Important
        "notify_url": "https://luthiradissanayeka.com/notify_url.php",
        "order_id": "<?php echo $order_id; ?>",
        "items": "Order payment",
        "amount": "<?php echo $totalPrice ; ?>",
        "currency": "<?php echo $currency; ?>",
        "hash": "<?php echo $hash; ?>", // Generated hash
        "first_name": "<?php echo $buyerDetails['Buyer_fname']; ?>",
        "last_name": "<?php echo $buyerDetails['Buyer_lname']; ?>",
        "email": "<?php echo $buyerDetails['Buyer_email']; ?>",
        "phone": "<?php echo $buyerDetails['Buyer_pnum']; ?>",
        "address": "<?php echo $buyerDetails['Buyer_address']; ?>",
        "city": "Colombo",
        "country": "Sri Lanka",
        "delivery_address": "<?php echo $buyerDetails['Buyer_address']; ?>",
        "delivery_city": "Kalutara",
        "delivery_country": "Sri Lanka",
        "custom_1": "",
        "custom_2": ""
    };

    // Show the payhere.js popup, when "PayHere Pay" is clicked
    document.getElementById('payhere-payment').onclick = function (e) {
        payhere.startPayment(payment);
    };
</script>

<?php include('footer.php'); ?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
