<?php
session_start();
include '../database/db_connect.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        echo "<script>alert('Login successful! Redirecting to home...'); window.location.href='index.php';</script>";
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
    <title>User Login</title>
   <!-- <link rel="stylesheet" href="../frontend/css/regandlog.css">-->
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
    width: 95%;
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
    <form action="login.php" method="POST">
        <h1 align="center">Login</h1>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <p><a href="forgot_password.php">Forgot Password?</a></p>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
        <p>Admin? <a href="admin_login.php">Login here</a></p>
        <p>Home page <a href="index.php">Home</a> 
    </form>
</body>
</html>
