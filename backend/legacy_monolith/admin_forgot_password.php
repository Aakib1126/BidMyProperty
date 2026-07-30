<?php
session_start();
include '../database/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $security_question = mysqli_real_escape_string($conn, $_POST['security_question']);
    $security_answer = mysqli_real_escape_string($conn, $_POST['security_answer']);
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    
    // Verify security question and answer
    $sql = "SELECT * FROM admins WHERE email='$email' AND security_question='$security_question'";
    $result = mysqli_query($conn, $sql);
    $admin = mysqli_fetch_assoc($result);
    
    if ($admin && password_verify($security_answer, $admin['security_answer'])) {
        mysqli_query($conn, "UPDATE admins SET password='$new_password' WHERE email='$email'");
        echo "<script>alert('Password reset successful! Please log in.'); window.location.href='admin_login.php';</script>";
    } else {
        echo "<script>alert('Incorrect details. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Forgot Password</title>
    <link rel="stylesheet" href="../frontend/css/admin_login.css">
</head>
<body>
    <div class="container">
        <h2>Reset Admin Password</h2>
        <form action="admin_forgot_password.php" method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <select name="security_question" required>
                <option value="What is your pet's name?">What is your pet's name?</option>
                <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
                <option value="What was your first car?">What was your first car?</option>
            </select>
            <input type="text" name="security_answer" placeholder="Enter your security answer" required>
            <input type="password" name="new_password" placeholder="Enter new password" required>
            <button type="submit">Reset Password</button>
        </form>
        <a href="admin_login.php">Back to Login</a>
    </div>
</body>
</html>