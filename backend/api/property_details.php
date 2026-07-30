<?php
require_once 'config.php';

// GET: fetch one property's details
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['id'])) {
        send_error('Invalid property!');
    }
    $property_id = (int) $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM properties WHERE id='$property_id' AND approved = '1'");
    $property = mysqli_fetch_assoc($result);

    if (!$property) {
        send_error('Property not found!', 404);
    }

    $property['images'] = !empty($property['images']) ? explode(',', $property['images']) : [];
    $property['videos'] = !empty($property['videos']) ? explode(',', $property['videos']) : [];
    send_json(['success' => true, 'property' => $property]);
}

// POST: place a bid on this property (mirrors the original property_details.php form)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = require_user();
    $property_id = (int) ($_POST['property_id'] ?? 0);
    $bid_amount = (float) ($_POST['bid_amount'] ?? 0);

    $res = mysqli_query($conn, "SELECT highest_bid FROM properties WHERE id='$property_id'");
    $row = mysqli_fetch_assoc($res);
    if (!$row) {
        send_error('Property not found!', 404);
    }
    $current_highest_bid = $row['highest_bid'];

    if ($bid_amount > $current_highest_bid) {
        mysqli_query($conn, "UPDATE properties SET highest_bid='$bid_amount' WHERE id='$property_id'");
        mysqli_query($conn, "INSERT INTO bids (user_id, property_id, bid_amount) VALUES ('$user_id', '$property_id', '$bid_amount')");
        send_success(['message' => 'Bid placed successfully!', 'new_bid' => $bid_amount]);
    } else {
        send_error('Your bid must be higher than the current highest bid.');
    }
}

send_error('Unsupported method.', 405);
