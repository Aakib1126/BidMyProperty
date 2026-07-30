<?php
// Database connection
include '../database/db_connect.php';

// Registration Logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $security_question = mysqli_real_escape_string($conn, $_POST['security_question']);
    $security_answer = password_hash($_POST['security_answer'], PASSWORD_DEFAULT);

    // Check if email already exists
    $check_email = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $check_email);
    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Email already registered!');</script>";
    } else {
        $sql = "INSERT INTO users (name, email, password, created_at, security_question, security_answer) 
        VALUES ('$name', '$email', '$password', NOW(), '$security_question', '$security_answer')";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Registration successful! Redirecting to login...'); window.location.href='login.php';</script>";
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
    <title>Register</title>
  <!--  <link rel="stylesheet" href="../frontend/css/regandlog.css">-->
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
    width: 95%;
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
    text-align:center;
}

input:focus {
    width: 90%;
    outline: none;
    border-color: #0072ff;
    box-shadow: 0 0 8px rgba(0, 114, 255, 0.5);
}

button {
    width: 97%;
    padding: 14px;
    border: none;
    border-radius: 6px;
    background: #00c6ff;
    color: white;
    font-size: 18px;
    cursor: pointer;
    transition: 0.3s;
    font-weight: bold;
   /* letter-spacing: -20px;*/
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

    <form action="register.php" method="POST">
        <h1 align="center">Register</h1>
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="security_question" required>
            <option value="What is your pet's name?">What is your pet's name?</option>
            <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
            <option value="What was your first car?">What was your first car?</option>
        </select>
        <input type="text" name="security_answer" placeholder="Security Answer" required>
        <button type="submit">Register</button>
        <p align="center">Already registered? <a href="login.php">Login here</a></p>
    </form>

</body>
</html>