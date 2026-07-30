<?php
require_once 'config.php';
$user_id = require_user();

// Mark as sold
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_sold'])) {
    $property_id = (int) $_POST['property_id'];
    mysqli_query($conn, "UPDATE properties SET status='sold' WHERE id='$property_id' AND user_id='$user_id'");
    send_success(['message' => 'Marked as sold.']);
}

// GET: list this user's uploaded properties
// NOTE: the original file had "WHERE id = '$user_id'" here, which looks like a
// copy-paste bug (comparing a property's id to the user's id). Fixed to
// "WHERE user_id = '$user_id'" so this actually returns the logged-in user's
// own properties.
$result = mysqli_query($conn, "SELECT * FROM properties WHERE user_id='$user_id'");
$properties = [];
while ($row = mysqli_fetch_assoc($result)) {
    $row['images'] = !empty($row['images']) ? explode(',', $row['images']) : [];
    $properties[] = $row;
}
send_json(['success' => true, 'properties' => $properties]);
