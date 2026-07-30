<?php
session_start();
include '../database/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $security_question = mysqli_real_escape_string($conn, $_POST['security_question']);
    $security_answer = mysqli_real_escape_string($conn, $_POST['security_answer']);
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    
    // Verify security question and answer
    $sql = "SELECT * FROM users WHERE email='$email' AND security_question='$security_question'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    
    if ($user && password_verify($security_answer, $user['security_answer'])) {
        mysqli_query($conn, "UPDATE users SET password='$new_password' WHERE email='$email'");
        echo "<script>alert('Password reset successful! Please log in.'); window.location.href='login.php';</script>";
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
    <title>Forgot Password</title>
<!--    <link rel="stylesheet" href="../frontend/css/regandlog.css">-->
<style>
    body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(to right, #1e3c72, #2a5298);
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.container {
    background: rgba(0, 0, 0, 0.85);
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.4);
    width: 380px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}


h1 {
    margin-bottom: 20px;
    color: #00c6ff;
    font-size: 26px;
}

.form-group {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 15px;
}

input {
    width: 90%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #00c6ff;
    border-radius: 6px;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    font-size: 16px;
    text-align: center;
    transition: 0.3s;
}

input::placeholder {
    color: #ddd;
}

input:focus {
    outline: none;
    border-color: #0072ff;
    box-shadow: 0 0 8px rgba(0, 114, 255, 0.5);
}

button {
    width: 93%;
    padding: 14px;
    border: none;
    border-radius: 6px;
    background: #00c6ff;
    color: white;
    font-size: 18px;
    cursor: pointer;
    transition: 0.3s;
    font-weight: bold;
    letter-spacing: 1px;
}

button:hover {
    background: #0072ff;
}

.link {
    margin-top: 15px;
}

.link a {
    color: #00c6ff;
    text-decoration: none;
    font-size: 14px;
    transition: 0.3s;
}

.link a:hover {
    color: #ffffff;
    text-decoration: underline;
}
a {
    color: #00c6ff;
}
</style>
</head>
<body>
    <form action="forgot_password.php" method="POST">
        <h1 align="center">Reset Password</h2>
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
</body>
</html>