<?php
session_start();
include '../database/db_connect.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must be logged in to change your role.'); window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch current user role
$query = "SELECT user_type FROM users WHERE id='$user_id'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $new_role = ($user['user_type'] == 'buyer') ? 'seller' : 'buyer';

    // Update role
    $update_query = "UPDATE users SET user_type='$new_role' WHERE id='$user_id'";
    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Role changed to $new_role successfully!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating role. Please try again.'); window.location.href='dashboard.php';</script>";
    }
} else {
    echo "<script>alert('User not found.'); window.location.href='dashboard.php';</script>";
}

mysqli_close($conn);
?>
