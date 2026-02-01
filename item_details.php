<?php
session_start();
include('db_connection.php');
$buyerLoggedIn = isset($_SESSION['Buyer_id']);

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$itemId = $_GET['id'];

$sql = "SELECT * FROM Items WHERE Item_id = '$itemId'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header('Location: index.php');
    exit();
}

$item = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $item['Item_Name']; ?> - Item Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .icon-dark i {
            color: #343a40;
        }
        .item-image {
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: cover;
        }
        .price {
            font-size: 1.5rem;
            color: #ED0002;
        }
        .quantity-input {
            width: 60px;
            text-align: center;
        }
        .custom-width {
            width: 150px; /* Adjust the width as needed */
        }
        .chat-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #007bff;
            color: white;
            border-radius: 50%;
            padding: 15px;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
        .chat-icon:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <img src="<?php echo $item['Images']; ?>" class="item-image" alt="Item Image">
        </div>
        <div class="col-md-6">
            <h1><?php echo $item['Item_Name']; ?></h1>
            <p class="price">LKR <?php echo $item['Prize']; ?></p>
            <p><?php echo $item['Description']; ?></p>
            <p><strong>Quantity: </strong><?php echo $item['Quantity']; ?></p>
            <form method="POST" action="add_to_cart.php">
                <input type="hidden" name="item_id" value="<?php echo $item['Item_id']; ?>">
                <div class="input-group mb-3 custom-width">
                    <button type="button" class="btn btn-outline-secondary" onclick="decreaseQuantity()">-</button>
                    <input type="number" class="form-control quantity-input" name="quantity" value="1" min="1" max="<?php echo $item['Quantity']; ?>" id="quantity">
                    <button type="button" class="btn btn-outline-secondary" onclick="increaseQuantity()">+</button>
                </div>
                <button type="submit" class="btn btn-primary">Add To Cart</button>
                <button type="button" class="btn btn-danger" id="buyNowButton" onclick="buyNow()">Buy Now</button>
            </form>
        </div>
    </div>
</div>

<!-- Chat Icon -->
<div class="chat-icon" data-bs-toggle="modal" data-bs-target="#chatModal">
    <i class="fas fa-comments"></i>
</div>

<!-- Chat Modal -->
<div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chatModalLabel">Chat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php include('chat_interface.php'); ?>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function increaseQuantity() {
        var quantityInput = document.getElementById('quantity');
        var currentQuantity = parseInt(quantityInput.value);
        if (currentQuantity < <?php echo $item['Quantity']; ?>) {
            quantityInput.value = currentQuantity + 1;
        }
    }

    function decreaseQuantity() {
        var quantityInput = document.getElementById('quantity');
        var currentQuantity = parseInt(quantityInput.value);
        if (currentQuantity > 1) {
            quantityInput.value = currentQuantity - 1;
        }
    }

    function buyNow() {
        var itemId = <?php echo $item['Item_id']; ?>;
        var quantity = document.getElementById('quantity').value;
        location.href = 'buy_now.php?id=' + itemId + '&quantity=' + quantity;
    }

    function openChat() {
        $('#chatModal').modal('show');
    }
</script>
</body>
</html>

