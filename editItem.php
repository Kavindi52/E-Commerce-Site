<?php
session_start();

if (!isset($_SESSION['seller_id'])) {
    header('Location: seller_loging.php');
    exit();
}

include('db_connection.php');

$seller_id = $_SESSION['seller_id'];

$sql = "SELECT * FROM Items WHERE seller_id = '$seller_id'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Items</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4">Edit Items</h1>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Item_id</th>
                    <th scope="col">Item_Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Description</th>
                    <th scope="col">Category</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Images</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["Item_id"] . "</td>";
                        echo "<td>" . $row["Item_Name"] . "</td>";
                        echo "<td>" . $row["Prize"] . "</td>";
                        echo "<td>" . $row["Description"] . "</td>";
                        echo "<td>" . $row["Category"] . "</td>";
                        echo "<td>" . $row["Quantity"] . "</td>";
                        echo '<td><img src="' . $row["Images"] . '" alt="Item Image" width="50"></td>';
                        echo '<td>
                                <button type="button" class="btn btn-primary btn-sm me-2" onclick="editItem(' . $row["Item_id"] . ')">Edit</button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteItem(' . $row["Item_id"] . ')">Delete</button>
                              </td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No items found</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function editItem(itemId) {
    window.location.href = 'edit.php?id=' + itemId;
}

function deleteItem(itemId) {
    if (confirm('Are you sure you want to delete this item?')) {
        window.location.href = 'delete_item.php?id=' + itemId;
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
