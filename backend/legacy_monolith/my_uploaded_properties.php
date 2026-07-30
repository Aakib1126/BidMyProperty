<?php
session_start();
include '../database/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle mark as sold
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mark_sold'])) {
    $property_id = $_POST['property_id'];
    $query = "UPDATE properties SET status = 'sold' WHERE id = $property_id AND user_id = $user_id";
    mysqli_query($conn, $query);
}

// Fetch user's properties
$sql = "SELECT * FROM properties WHERE id = '$user_id'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage My Properties</title>
    <link rel="stylesheet" href="../frontend/css/manage.css">
</head>
<body>
    <h1>My Uploaded Properties</h1>
    <a href="upload_property.php">+ Add New Property</a>
    <div class="property-list">
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <div class="property-card">
                <h3><?php echo $row['title']; ?></h3>
                <p><strong>Price:</strong> $<?php echo $row['current_price']; ?></p>
                <p><strong>Status:</strong> <?php echo ucfirst($row['status']); ?></p>

                <?php if (!empty($row['images'])): 
                    $images = explode(",", $row['images']);
                ?>
                    <img src="uploads/images/<?php echo $images[0]; ?>" alt="Property Image" width="200">
                <?php endif; ?>

                <?php if ($row['status'] === 'available'): ?>
                    <form method="POST">
                        <input type="hidden" name="property_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="mark_sold">Mark as Sold</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
