<?php
session_start();
include '../database/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM admins WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    $admin = mysqli_fetch_assoc($result);
    
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        echo "<script>alert('Login successful! Redirecting to dashboard...'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Invalid email or password. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
  <!--  <link rel="stylesheet" href="../frontend/css/admin_login.css">-->
<style>
    body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(to right, #141e30, #243b55);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    background: rgba(255, 255, 255, 0.1);
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
    text-align: center;
    width: 350px;
}

h1 {
    color: white;
    margin-bottom: 20px;
}

input {
    width: 90%;
    padding: 12px;
    margin: 10px 0;
    border: none;
    border-radius: 5px;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    font-size: 16px;
}

input::placeholder {
    color: #ddd;
    text-align:center;
}

button {
    width: 97%;
    padding: 12px;
    border: none;
    border-radius: 5px;
    background: #00c6ff;
    color: white;
    font-size: 18px;
    cursor: pointer;
    transition: 0.3s;
    letter-spacing: 1px;
}

button:hover {
    background: #0072ff;
}

a {
    color: #00c6ff;
    text-decoration: none;
    display: block;
    margin-top: 10px;
    transition: 0.3s;
}

a:hover {
    color: #ffffff;
} 
    </style>

</head>
<body>
    <div class="container">
        <form action="admin_login.php" method="POST">
            <h1 align="center">Admin Login</h1>
            <input type="email" name="email" placeholder="Admin Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <a href="admin_forgot_password.php">Forgot Password?</a>
            <a href="admin_register.php">New Admin</a>
            <a href="login.php">User Login</a>
        </form>
    </div>
</body>
</html>
