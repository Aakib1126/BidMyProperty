<?php
require_once 'config.php';
$user_id = require_user();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_error('POST required.', 405);
}

$title = mysqli_real_escape_string($conn, $_POST['title'] ?? '');
$description = mysqli_real_escape_string($conn, $_POST['description'] ?? '');
$current_price = (float) ($_POST['current_price'] ?? 0);
$category = mysqli_real_escape_string($conn, $_POST['category'] ?? '');

$upload_dir = __DIR__ . '/../uploads/';

// Image upload
$image_names = [];
if (!empty($_FILES['images']['tmp_name'])) {
    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        if ($tmp_name === '' || $_FILES['images']['error'][$key] !== UPLOAD_ERR_OK) continue;
        $filename = basename($_FILES['images']['name'][$key]);
        move_uploaded_file($tmp_name, $upload_dir . 'images/' . $filename);
        $image_names[] = $filename;
    }
}

// Video upload
$video_names = [];
if (!empty($_FILES['videos']['tmp_name'])) {
    foreach ($_FILES['videos']['tmp_name'] as $key => $tmp_name) {
        if ($tmp_name === '' || $_FILES['videos']['error'][$key] !== UPLOAD_ERR_OK) continue;
        $filename = basename($_FILES['videos']['name'][$key]);
        move_uploaded_file($tmp_name, $upload_dir . 'videos/' . $filename);
        $video_names[] = $filename;
    }
}

$images = implode(',', $image_names);
$videos = implode(',', $video_names);

$sql = "INSERT INTO properties (user_id, title, description, current_price, highest_bid, category, images, videos, status)
        VALUES ('$user_id', '$title', '$description', '$current_price', '$current_price', '$category', '$images', '$videos', 'pending')";

if (mysqli_query($conn, $sql)) {
    send_success(['message' => 'Property uploaded and awaiting approval.']);
} else {
    send_error('Error uploading property: ' . mysqli_error($conn));
}
