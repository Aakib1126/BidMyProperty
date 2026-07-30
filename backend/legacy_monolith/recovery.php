<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "bidmyproperty");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Step 1: Verify Email and Security Question
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify_email'])) {
    $email = $_POST['email'];
    $_SESSION['email'] = $email;
    
    $sql = "SELECT security_question FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['security_question'] = $row['security_question'];
    } else {
        echo "<script>alert('Email not found!');</script>";
    }
}

// Step 2: Verify Answer and Reset Password
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify_answer'])) {
    $security_answer = $_POST['security_answer'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $email = $_SESSION['email'];
    
    $sql = "SELECT * FROM users WHERE email='$email' AND security_answer='$security_answer'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $update_sql = "UPDATE users SET password='$new_password' WHERE email='$email'";
        if (mysqli_query($conn, $update_sql)) {
            echo "<script>alert('Password updated successfully!'); window.location.href='login.php';</script>";
        } else {
            echo "Error updating password: " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('Incorrect security answer!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Recovery</title>
    <link rel="stylesheet" href="../frontend/css/styles.css">
</head>
<body>
    <h2>Password Recovery</h2>
    <form action="password_recovery.php" method="POST">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit" name="verify_email">Verify Email</button>
    </form>
    
    <?php if (isset($_SESSION['security_question'])): ?>
    <form action="password_recovery.php" method="POST">
        <p><strong>Security Question:</strong> <?php echo $_SESSION['security_question']; ?></p>
        <input type="text" name="security_answer" placeholder="Enter your answer" required>
        <input type="password" name="new_password" placeholder="Enter new password" required>
        <button type="submit" name="verify_answer">Verify & Reset</button>
    </form>
    <?php endif; ?>
</body>
</html>
