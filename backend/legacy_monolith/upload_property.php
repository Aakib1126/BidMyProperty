<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include '../database/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $current_price = $_POST['current_price'];
    $category = $_POST['category'];
    $user_id = $_SESSION['user_id'];

    // Image upload
    $image_names = [];
    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        $filename = basename($_FILES['images']['name'][$key]);
        $target = "uploads/images/" . $filename;
        move_uploaded_file($tmp_name, $target);
        $image_names[] = $filename;
    }

    // Video upload
    $video_names = [];
    foreach ($_FILES['videos']['tmp_name'] as $key => $tmp_name) {
        $filename = basename($_FILES['videos']['name'][$key]);
        $target = "uploads/videos/" . $filename;
        move_uploaded_file($tmp_name, $target);
        $video_names[] = $filename;
    }

    $images = implode(",", $image_names);
    $videos = implode(",", $video_names);

    $sql = "INSERT INTO properties (user_id, title, description, current_price, highest_bid, category, images, videos, status)
            VALUES ('$user_id', '$title', '$description', '$current_price', '$current_price', '$category', '$images', '$videos', 'pending')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Property uploaded and awaiting approval.'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Error uploading property.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Property</title>
    <link rel="stylesheet" href="../frontend/css/upload_style.css">
</head>
<body>
    <style>
        body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #1e3c72, #2a5298);
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.upload-container {
    background: #fff;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 0 20px rgba(0,0,0,0.2);
    width: 400px;
    text-align: center;
}

.upload-container h2 {
    margin-bottom: 20px;
    color: #2a5298;
}

.upload-container form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

input[type="text"],
input[type="number"],
textarea,
select {
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 15px;
}

input[type="file"] {
    border: none;
}

button {
    padding: 12px;
    background: #2a5298;
    color: white;
    font-size: 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background: #1e3c72;
}

label {
    text-align: left;
    font-weight: bold;
    color: #444;
}

        </style>
<div class="upload-container">
    <h2>Upload Property</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Property Title" required>
        <textarea name="description" placeholder="Description" rows="4" required></textarea>
        <input type="number" name="current_price" placeholder="Starting Price" required>
        <select name="category" required>
            <option value="">Select Category</option>
            <option value="residential">Residential</option>
            <option value="commercial">Commercial</option>
        </select>
        <label>Upload Images</label>
        <input type="file" name="images[]" multiple accept="image/*" required>
        <label>Upload Videos</label>
        <input type="file" name="videos[]" multiple accept="video/*">
        <button type="submit">Upload Property</button>
        <a href="index.php"> Back To Home </a>
    </form>
</div>
</body>
</html>
