<?php
require_once 'config.php';

// Only insert a new review when the form was actually submitted (name present),
// so calling this endpoint just to load the review list doesn't create blank rows.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['name'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $rating = intval($_POST['rating'] ?? 0);
    $message = mysqli_real_escape_string($conn, $_POST['message'] ?? '');

    $sql = "INSERT INTO reviews (name, email, rating, message) VALUES ('$name', '$email', '$rating', '$message')";
    if (!mysqli_query($conn, $sql)) {
        send_error('Error: ' . mysqli_error($conn));
    }
    // Falls through to also return the updated review list below.
}

$reviews_result = mysqli_query($conn, "SELECT * FROM reviews ORDER BY created_at DESC");
$reviews = [];
while ($row = mysqli_fetch_assoc($reviews_result)) {
    $reviews[] = $row;
}

send_json(['success' => true, 'message' => 'Review submitted successfully!', 'reviews' => $reviews]);
