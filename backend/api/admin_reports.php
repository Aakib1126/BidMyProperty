<?php
require_once 'config.php';
require_admin();

$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'];
$total_properties = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM properties"))['total'];
$total_bids = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM bids"))['total'];
$total_revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(bid_amount) AS revenue FROM bids"))['revenue'];

send_json([
    'success' => true,
    'total_users' => $total_users,
    'total_properties' => $total_properties,
    'total_bids' => $total_bids,
    'total_revenue' => $total_revenue !== null ? number_format((float) $total_revenue, 2) : '0.00',
]);
