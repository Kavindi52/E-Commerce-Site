<?php
session_start();

if (!isset($_SESSION['seller_id'])) {
    header('Location: seller_loging.php');
    exit();
}

include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $itemId = $_POST['Item_id'];
    $itemName = $_POST['Item_Name'];
    $price = $_POST['Prize'];
    $description = $_POST['Description'];
    $category = $_POST['Category'];
    $quantity = $_POST['Quantity'];
    $images = $_POST['Images'];

    $sql = "UPDATE Items SET Item_Name='$itemName', Prize='$price', Description='$description', Category='$category', Quantity='$quantity', Images='$images' WHERE Item_id='$itemId' AND seller_id='{$_SESSION['seller_id']}'";

    if ($conn->query($sql) === TRUE) {
        header('Location: editItem.php');
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$itemId = $_GET['id'];
$sql = "SELECT * FROM Items WHERE Item_id='$itemId' AND seller_id='{$_SESSION['seller_id']}'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
} else {
    header('Location: edit_items.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4">Edit Item</h1>
    <form method="post" action="">
        <div class="mb-3">
            <label for="Item_id" class="form-label">Item ID</label>
            <input type="text" class="form-control" id="Item_id" name="Item_id" value="<?php echo $row['Item_id']; ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="Item_Name" class="form-label">Item Name</label>
            <input type="text" class="form-control" id="Item_Name" name="Item_Name" value="<?php echo $row['Item_Name']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="Prize" class="form-label">Price</label>
            <input type="text" class="form-control" id="Prize" name="Prize" value="<?php echo $row['Prize']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="Description" class="form-label">Description</label>
            <textarea class="form-control" id="Description" name="Description" rows="3" required><?php echo $row['Description']; ?></textarea>
        </div>
        <div class="mb-3">
            <label for="Category" class="form-label">Category</label>
            <input type="text" class="form-control" id="Category" name="Category" value="<?php echo $row['Category']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="Quantity" class="form-label">Quantity</label>
            <input type="text" class="form-control" id="Quantity" name="Quantity" value="<?php echo $row['Quantity']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="Images" class="form-label">Images</label>
            <input type="text" class="form-control" id="Images" name="Images" value="<?php echo $row['Images']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="seller_dashboard.php" class="btn btn-secondary">Back</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
