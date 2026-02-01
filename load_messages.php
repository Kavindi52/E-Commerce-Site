<?php
include('db_connection.php');

$sql = "SELECT * FROM messages ORDER BY timestamp ASC";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo '<div class="chat-message"><strong>' . htmlspecialchars($row['user']) . ':</strong> ' . htmlspecialchars($row['message']) . '</div>';
}
?>
