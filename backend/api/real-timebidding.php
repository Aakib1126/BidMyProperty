<?php
require_once 'config.php';

// GET: list all properties for the live-bidding board
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = mysqli_query($conn, "SELECT * FROM properties");
    $properties = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $properties[] = $row;
    }
    send_json(['success' => true, 'properties' => $properties]);
}

// POST: place a bid
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_bid'])) {
    $user_id = require_user();
    $property_id = (int) ($_POST['property_id'] ?? 0);
    $bid_amount = (float) ($_POST['bid_amount'] ?? 0);

    $result = mysqli_query($conn, "SELECT highest_bid FROM properties WHERE id='$property_id'");
    $row = mysqli_fetch_assoc($result);
    if (!$row) send_error('Property not found.', 404);

    if ($bid_amount > $row['highest_bid']) {
        mysqli_query($conn, "UPDATE properties SET highest_bid='$bid_amount' WHERE id='$property_id'");
        mysqli_query($conn, "INSERT INTO bids (user_id, property_id, bid_amount) VALUES ('$user_id', '$property_id', '$bid_amount')");
        send_json(['success' => true, 'new_bid' => $bid_amount]);
    } else {
        send_json(['success' => false, 'message' => 'Your bid must be higher than the current highest bid.']);
    }
}
