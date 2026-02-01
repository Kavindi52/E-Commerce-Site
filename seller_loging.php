<?php
session_start();

include('db_connection.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form inputs
    $user = $_POST['user'];
    $password = $_POST['password'];

    // Prepare and execute SQL statement
    $stmt = $conn->prepare("SELECT * FROM Seller_Registration WHERE Seller_email = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Fetch user data
        $row = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Start session
            $_SESSION['seller_id'] = $row['seller_id']; 
            $_SESSION['seller_email'] = $row['seller_email'];
            $_SESSION['seller_fname'] = $row['Seller_fname'];
            $_SESSION['seller_lname'] = $row['Seller_lname'];

            // Redirect to seller dashboard
            header('Location: seller_dashboard.php'); 
            exit();
        } else {
            $login_error = "Incorrect password!";
        }
    } else {
        $login_error = "User not found!";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card" style="width: 100%; max-width: 500px;">
        <div class="card-header text-center">
            <h4>Login As Seller</h4>
        </div>
        <div class="card-body">
            <?php if (isset($login_error)) : ?>
                <div class="alert alert-danger text-center"><?php echo $login_error; ?></div>
            <?php endif; ?>

            <form name="form" id="form" method="POST" action="">
                <div class="form-group">
                    <label for="user">E-Mail</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="glyphicon glyphicon-user"></i></span>
                        </div>
                        <input type="email" class="form-control" id="user" name="user" placeholder="E-Mail" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="glyphicon glyphicon-lock"></i></span>
                        </div>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block mb-3"><i class="glyphicon glyphicon-log-in"></i> Login As Seller</button>
                <div class="d-flex justify-content-between">
                    <a href="seller_register.php" class="btn btn-secondary"><i class="glyphicon glyphicon-user"></i> Register</a>
                    <a href="loging.php" class="btn btn-info"><i class="glyphicon glyphicon-new-window"></i> Login As Buyer</a>
                </div>
                <div class="text-center mt-3">
                    <a href="#" class="btn btn-link">Forgotten password?</a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
