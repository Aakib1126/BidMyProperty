<?php
require_once 'config.php';
require_admin();

// Delete a user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $user_id = (int) $_POST['delete'];
    mysqli_query($conn, "DELETE FROM users WHERE id='$user_id'");
    send_success(['message' => 'User deleted successfully!']);
}

// List all users
$result = mysqli_query($conn, "SELECT * FROM users");
$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    unset($row['password'], $row['security_answer']); // never send hashes to the client
    $users[] = $row;
}
send_json(['success' => true, 'users' => $users]);
