<?php
require_once 'config.php';
require_admin();

$user_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'];
$property_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM properties"))['total'];
$bid_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM bids"))['total'];

send_json([
    'success' => true,
    'user_count' => $user_count,
    'property_count' => $property_count,
    'bid_count' => $bid_count,
]);
