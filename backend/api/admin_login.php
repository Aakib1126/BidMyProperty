<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_error('POST required.', 405);
}

$email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$result = mysqli_query($conn, "SELECT * FROM admins WHERE email='$email'");
$admin = mysqli_fetch_assoc($result);

if ($admin && password_verify($password, $admin['password'])) {
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_name'] = $admin['name'];
    send_success(['message' => 'Login successful!']);
} else {
    send_error('Invalid email or password. Please try again.', 401);
}
