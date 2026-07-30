<?php
// Database connection
include '../database/db_connect.php';
// Admin Registration Logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $security_question = mysqli_real_escape_string($conn, $_POST['security_question']);
    $security_answer = password_hash($_POST['security_answer'], PASSWORD_DEFAULT);
    
    // Check if admin email already exists
    $check_email = "SELECT * FROM admins WHERE email = '$email'";
    $result = mysqli_query($conn, $check_email);
    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Admin email already registered!');</script>";
    } else {
        $sql = "INSERT INTO admins (name, email, password, security_question, security_answer, created_at) 
                VALUES ('$name', '$email', '$password', '$security_question', '$security_answer', NOW())";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Admin registration successful! Redirecting to login...'); window.location.href='admin_login.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <link rel="stylesheet" href="../frontend/css/admin_login.css">
</head>
<body>
    <div class="container">
        <form action="admin_register.php" method="POST">
            <h1>Admin Registration</h1>
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Admin Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="security_question" required>
                <option value="What is your pet's name?">What is your pet's name?</option>
                <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
                <option value="What was your first car?">What was your first car?</option>
            </select>
            <input type="text" name="security_answer" placeholder="Security Answer" required>
            <button type="submit">Register</button>
            <a href="admin_login.php">Already an admin? Login here</a>
        </form>
    </div>
</body>
</html>