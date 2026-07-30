<?php
require_once 'config.php';

$response = ['logged_in' => false, 'role' => null, 'name' => null, 'user_type' => null];

if (isset($_SESSION['user_id'])) {
    $user_id = (int) $_SESSION['user_id'];
    $result = mysqli_query($conn, "SELECT name, user_type FROM users WHERE id='$user_id'");
    $user = mysqli_fetch_assoc($result);
    $response['logged_in'] = true;
    $response['role'] = 'user';
    $response['name'] = $_SESSION['user_name'] ?? ($user['name'] ?? null);
    $response['user_type'] = $user['user_type'] ?? null;
} elseif (isset($_SESSION['admin_id'])) {
    $response['logged_in'] = true;
    $response['role'] = 'admin';
    $response['name'] = $_SESSION['admin_name'] ?? null;
}

send_json($response);
