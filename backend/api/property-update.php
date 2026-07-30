<?php
require_once 'config.php';
$owner_id = require_user();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['add_property'])) {
    send_error('POST with add_property required.', 405);
}

$title = mysqli_real_escape_string($conn, $_POST['title'] ?? '');
$description = mysqli_real_escape_string($conn, $_POST['description'] ?? '');
$price = (float) ($_POST['price'] ?? 0);
$status = mysqli_real_escape_string($conn, $_POST['status'] ?? 'available');

$upload_dir = __DIR__ . '/../uploads/';
$image = $_FILES['image']['name'] ?? '';
$document = $_FILES['document']['name'] ?? '';

if (!empty($image)) {
    move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . 'images/' . basename($image));
}
if (!empty($document)) {
    move_uploaded_file($_FILES['document']['tmp_name'], $upload_dir . 'documents/' . basename($document));
}

$image = mysqli_real_escape_string($conn, $image);
$document = mysqli_real_escape_string($conn, $document);

$sql = "INSERT INTO properties (title, description, current_price, highest_bid, status, owner_id, image, document)
        VALUES ('$title', '$description', '$price', '0', '$status', '$owner_id', '$image', '$document')";

if (mysqli_query($conn, $sql)) {
    send_success(['message' => 'Property added successfully!']);
} else {
    send_error('Error: ' . mysqli_error($conn));
}
