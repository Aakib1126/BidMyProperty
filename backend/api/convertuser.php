<?php
require_once 'config.php';
$user_id = require_user();

$result = mysqli_query($conn, "SELECT user_type FROM users WHERE id='$user_id'");
if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $new_role = ($user['user_type'] == 'buyer') ? 'seller' : 'buyer';

    if (mysqli_query($conn, "UPDATE users SET user_type='$new_role' WHERE id='$user_id'")) {
        send_success(['message' => "Role changed to $new_role successfully!", 'user_type' => $new_role]);
    } else {
        send_error('Error updating role. Please try again.');
    }
} else {
    send_error('User not found.');
}
