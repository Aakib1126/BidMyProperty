<?php
require_once 'config.php';

// Step 1: verify email, return the security question (mirrors original recovery.php)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_email'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $result = mysqli_query($conn, "SELECT security_question FROM users WHERE email='$email'");

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['email'] = $email;
        $_SESSION['security_question'] = $row['security_question'];
        send_success(['security_question' => $row['security_question']]);
    } else {
        send_error('Email not found!');
    }
}

// Step 2: verify answer, reset password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_answer'])) {
    if (!isset($_SESSION['email'])) {
        send_error('Please verify your email first.');
    }
    $email = $_SESSION['email'];
    $security_answer = mysqli_real_escape_string($conn, $_POST['security_answer'] ?? '');
    $new_password = password_hash($_POST['new_password'] ?? '', PASSWORD_DEFAULT);

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND security_answer='$security_answer'");

    if (mysqli_num_rows($result) > 0) {
        if (mysqli_query($conn, "UPDATE users SET password='$new_password' WHERE email='$email'")) {
            unset($_SESSION['email'], $_SESSION['security_question']);
            send_success(['message' => 'Password updated successfully!']);
        } else {
            send_error('Error updating password: ' . mysqli_error($conn));
        }
    } else {
        send_error('Incorrect security answer!');
    }
}

send_error('Invalid request.');
