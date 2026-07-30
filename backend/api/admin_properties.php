<?php
require_once 'config.php';
require_admin();

// Approve
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve'])) {
    $id = (int) $_POST['approve'];
    mysqli_query($conn, "UPDATE properties SET approved = 1 WHERE id='$id'");
    send_success(['message' => 'Property approved.']);
}

// Reject/delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = (int) $_POST['delete'];
    mysqli_query($conn, "DELETE FROM properties WHERE id='$id'");
    send_success(['message' => 'Property deleted.']);
}

// List pending (unapproved) properties
$result = mysqli_query($conn, "SELECT * FROM properties WHERE approved = 0");
$properties = [];
while ($row = mysqli_fetch_assoc($result)) {
    $properties[] = $row;
}
send_json(['success' => true, 'properties' => $properties]);
