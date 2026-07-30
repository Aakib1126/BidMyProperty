<?php
session_start();
include '../database/db_connect.php';

// Property Addition & Editing
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_property'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $status = $_POST['status'];
    $owner_id = $_SESSION['user_id'];
    
    $image = $_FILES['image']['name'];
    $document = $_FILES['document']['name'];
    
    $image_target = "uploads/images/" . basename($image);
    $document_target = "uploads/documents/" . basename($document);
    
    move_uploaded_file($_FILES['image']['tmp_name'], $image_target);
    move_uploaded_file($_FILES['document']['tmp_name'], $document_target);
    
    $sql = "INSERT INTO properties (title, description, current_price, highest_bid, status, owner_id, image, document) 
            VALUES ('$title', '$description', '$price', '0', '$status', '$owner_id', '$image', '$document')";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Property added successfully!'); window.location.href='propertylisting.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Properties</title>
    <link rel="stylesheet" href="../frontend/css/styles.css">
</head>
<body>
    <h2>update property</h2>
    <form action="property_management.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Property Title" required>
        <textarea name="description" placeholder="Property Description" required></textarea>
        <input type="number" name="price" placeholder="Price" required>
        <select name="status" required>
            <option value="available">Available</option>
            <option value="sold">Sold</option>
            <option value="pending">Pending</option>
        </select>
        <input type="file" name="image" accept="image/*" required>
        <input type="file" name="document" accept=".pdf,.docx" required>
        <button type="submit" name="add_property">Add Property</button>
    </form>
</body>
</html>
