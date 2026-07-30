<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_error('POST required.', 405);
}

$email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
$user = mysqli_fetch_assoc($result);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    send_success(['message' => 'Login successful!']);
} else {
    send_error('Invalid email or password. Please try again.', 401);
}
