<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_error('POST required.', 405);
}

$name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
$email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
$password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);
$security_question = mysqli_real_escape_string($conn, $_POST['security_question'] ?? '');
$security_answer = password_hash($_POST['security_answer'] ?? '', PASSWORD_DEFAULT);

$check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
if (mysqli_num_rows($check) > 0) {
    send_error('Email already registered!');
}

$sql = "INSERT INTO users (name, email, password, created_at, security_question, security_answer)
        VALUES ('$name', '$email', '$password', NOW(), '$security_question', '$security_answer')";

if (mysqli_query($conn, $sql)) {
    send_success(['message' => 'Registration successful! Please log in.']);
} else {
    send_error('Error: ' . mysqli_error($conn));
}
