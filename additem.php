<?php
session_start();

if (!isset($_SESSION['seller_id'])) {
    header('Location:seller_loging.php'); // Redirect to login page if seller_id is not set
    exit();
}

include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form inputs
    $itemName = $_POST['itemName'];
    $category = $_POST['category'];
    $prize = $_POST['prize'];
    $quantity = $_POST['quantity'];
    $description = $_POST['Description'];
    $seller_id = $_SESSION['seller_id']; // Retrieve seller_id from session

    // Handle file uploads
    $imageNames = [];
    if (!empty($_FILES['images']['name'][0])) {
        $uploadDir = 'ItemImg/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        foreach ($_FILES['images']['name'] as $key => $imageName) {
            $uploadFilePath = $uploadDir . basename($imageName);
            if (move_uploaded_file($_FILES['images']['tmp_name'][$key], $uploadFilePath)) {
                $imageNames[] = $uploadFilePath;
            } else {
                $message = "Failed to upload file: " . $imageName;
                break;
            }
        }
    }
    $images = implode(",", $imageNames);

    // Insert data into database
    if (!isset($message)) {
        $sql = "INSERT INTO Items (seller_id, Item_Name, Prize, Description, Category, Quantity, Images)
                VALUES ('$seller_id','$itemName', '$prize', '$description', '$category', '$quantity', '$images')";

        if ($conn->query($sql) === TRUE) {
            $message = "New item added successfully";
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Item</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
        }
        .form-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            margin-top: 50px;
        }
        .form-container h2 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center">
    <div class="form-container">
        <h2>Add Item</h2>
        <?php if (isset($message)) { echo "<div class='alert alert-info'>$message</div>"; } ?>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="itemName">Item Name</label>
                    <input type="text" class="form-control" id="itemName" name="itemName" placeholder="Item Name" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="category">Category</label>
                    <select id="category" class="form-control" name="category" required>
                        <option value="" disabled selected>Item Category</option>
                        <option value="Electronic Items">Electronic Items</option>
                        <option value="Babies & Toys">Babies & Toys</option>
                        <option value="Fashion Items">Fashion Items</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="prize">Prize</label>
                    <input type="number" class="form-control" id="prize" name="prize" placeholder="Item Prize" required> 
                </div>
                <div class="form-group col-md-6">
                    <label for="quantity">Quantity</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Quantity" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="Description">Description</label>
                    <input type="text" class="form-control" id="Description" name="Description" placeholder="Description" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="inputImages">Images</label>
                    <input type="file" class="form-control" id="inputImages" name="images[]" accept="image/png, image/jpeg" multiple>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary">Submit Item</button>
                    <a href="seller_dashboard.php" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
