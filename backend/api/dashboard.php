<?php
require_once 'config.php';
$user_id = require_user();

$user_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name, email FROM users WHERE id='$user_id'"));

$bid_query = "SELECT b.bid_amount, p.title, b.bid_time,
              CASE WHEN b.bid_amount = p.highest_bid THEN 'Winning' ELSE 'Outbid' END AS status
              FROM bids b JOIN properties p ON b.property_id = p.id WHERE b.user_id = '$user_id'";
$bid_result = mysqli_query($conn, $bid_query);
$bids = [];
while ($row = mysqli_fetch_assoc($bid_result)) {
    $bids[] = $row;
}

$watchlist_query = "SELECT p.title, p.current_price FROM watchlist w JOIN properties p ON w.property_id = p.id WHERE w.user_id = '$user_id'";
$watchlist_result = mysqli_query($conn, $watchlist_query);
$watchlist = [];
while ($row = mysqli_fetch_assoc($watchlist_result)) {
    $watchlist[] = $row;
}

send_json([
    'success' => true,
    'user' => $user_result,
    'bids' => $bids,
    'watchlist' => $watchlist,
]);
