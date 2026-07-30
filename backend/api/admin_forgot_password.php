<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_error('POST required.', 405);
}

$email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
$security_question = mysqli_real_escape_string($conn, $_POST['security_question'] ?? '');
$security_answer = $_POST['security_answer'] ?? '';
$new_password = password_hash($_POST['new_password'] ?? '', PASSWORD_DEFAULT);

$sql = "SELECT * FROM admins WHERE email='$email' AND security_question='$security_question'";
$result = mysqli_query($conn, $sql);
$admin = mysqli_fetch_assoc($result);

if ($admin && password_verify($security_answer, $admin['security_answer'])) {
    mysqli_query($conn, "UPDATE admins SET password='$new_password' WHERE email='$email'");
    send_success(['message' => 'Password reset successful! Please log in.']);
} else {
    send_error('Incorrect details. Please try again.');
}
