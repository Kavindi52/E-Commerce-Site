<?php
$servername = "localhost";
$username = "u935400905_fNyVA";
$password = "Cd123-3164";
$dbname = "u935400905_gsQRc";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>