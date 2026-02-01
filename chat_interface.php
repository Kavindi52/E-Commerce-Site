<?php
session_start();
include('db_connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #chat-box {
            width: 100%;
            height: 400px;
            overflow-y: scroll;
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
        .chat-message {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="mt-4">Chat System</h2>
    <div id="chat-box"></div>
    <div class="input-group">
        <input type="text" id="chat-message" class="form-control" placeholder="Type a message...">
        <button class="btn btn-primary" id="send-message">Send</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
$(document).ready(function() {
    function loadMessages() {
        $.ajax({
            url: 'load_messages.php',
            method: 'GET',
            success: function(data) {
                $('#chat-box').html(data);
                $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
            }
        });
    }

    $('#send-message').on('click', function() {
        var message = $('#chat-message').val();
        if (message.trim() != '') {
            $.ajax({
                url: 'send_message.php',
                method: 'POST',
                data: { message: message },
                success: function(data) {
                    $('#chat-message').val('');
                    loadMessages();
                }
            });
        }
    });

    loadMessages();
    setInterval(loadMessages, 3000); // Refresh chat every 3 seconds
});
</script>
</body>
</html>
