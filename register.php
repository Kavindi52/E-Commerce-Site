<?php

include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $address = $_POST['address'];
    $pnum = $_POST['pnum'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $Cpassword = $_POST['Cpassword'];

    // Check if passwords match
    if ($password !== $Cpassword) {
        echo "<div class='alert alert-danger text-center'>Passwords do not match!</div>";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO Buyer_Registration (Buyer_fname, Buyer_lname, Buyer_address, Buyer_pnum, Buyer_email, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $fname, $lname, $address, $pnum, $email, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            echo "<div class='alert alert-success text-center'>Buyer registration successful!</div>";
        } else {
            echo "<div class='alert alert-danger text-center'>Error: " . $stmt->error . "</div>";
        }

        // Close statement
        $stmt->close();
    }

    // Close connection
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card" style="width: 100%; max-width: 500px;">
        <div class="card-header text-center">
            <h4>Register As Buyer</h4>
        </div>
        <div class="card-body">
            <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($login_error)) : ?>
                <div class='alert alert-danger text-center'><?php echo $login_error; ?></div>
            <?php endif; ?>

            <form name="form" id="form" method="POST">
                <div class="form-group">
                    <label for="fname">First Name</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="glyphicon glyphicon-user"></i></span>
                        </div>
                        <input type="text" class="form-control" id="fname" name="fname" placeholder="First Name" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="lname">Last Name</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="glyphicon glyphicon-user"></i></span>
                        </div>
                        <input type="text" class="form-control" id="lname" name="lname" placeholder="Last Name" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="glyphicon glyphicon-envelope"></i></span>
                        </div>
                        <input type="text" class="form-control" id="address" name="address" placeholder="Address" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="pnum">Phone Number</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="glyphicon glyphicon-phone-alt"></i></span>
                        </div>
                        <input type="text" class="form-control" id="pnum" name="pnum" placeholder="Phone Number" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">E-Mail</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="glyphicon glyphicon-globe"></i></span>
                        </div>
                        <input type="email" class="form-control" id="email" name="email" placeholder="E-Mail" required>
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
                <div class="form-group">
                    <label for="Cpassword">Confirm Password</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="glyphicon glyphicon-ok"></i></span>
                        </div>
                        <input type="password" class="form-control" id="Cpassword" name="Cpassword" placeholder="Confirm Password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block mb-3"><i class="glyphicon glyphicon-log-in"></i> SignIn As Buyer</button>
                <div class="d-flex justify-content-between">
                    <a href="seller_register.php" class="btn btn-secondary"><i class="glyphicon glyphicon-user"></i> Register As Seller</a>
                    <a href="loging.php" class="btn btn-info"><i class="glyphicon glyphicon-new-window"></i> Already Register</a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
