<?php
require_once 'config.php';
$admin_id = require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE admins SET name='$name', email='$email', password='$password' WHERE id='$admin_id'");
    } else {
        mysqli_query($conn, "UPDATE admins SET name='$name', email='$email' WHERE id='$admin_id'");
    }

    $_SESSION['admin_name'] = $name;
    send_success(['message' => 'Settings updated successfully!']);
}

// GET: return current admin details
$admin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, name, email FROM admins WHERE id='$admin_id'"));
send_json(['success' => true, 'admin' => $admin]);
