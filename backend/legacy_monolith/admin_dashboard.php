<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
$conn = mysqli_connect("localhost", "root", "", "bidmyproperty");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch user count
$user_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'];
// Fetch property count
$property_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM properties"))['total'];
// Fetch bid count
$bid_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM bids"))['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../frontend/css/admin.css">
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <nav>
            <a href="admin_users.php">Manage Users</a>
            <a href="admin_properties.php">Manage Properties</a>
            <a href="admin_reports.php">View Reports</a>
            <a href="admin_settings.php">Settings</a>
            <a href="admin_logout.php">Logout</a>
        </nav>
    </header>
    <section>
        <h2>Website Overview</h2>
        <p><strong>Total Users:</strong> <?php echo $user_count; ?></p>
        <p><strong>Total Properties:</strong> <?php echo $property_count; ?></p>
        <p><strong>Total Bids:</strong> <?php echo $bid_count; ?></p>
    </section>
</body>
</html>
<?php
/*session_start();

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");  // Redirect to login if not logged in or not admin
    exit();
}

// Database connection
$mysqli = new mysqli("localhost", "root", "", "bidmyproperty");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch all pending properties
$query = "SELECT * FROM properties WHERE status = 'pending'";
$result = $mysqli->query($query);*/
?>

<!--<h2>Admin Dashboard - Property Approval</h2>
<table border="1">
    <tr>
        <th>Title</th>
        <th>Description</th>
        <th>Price</th>
        <th>Image</th>
        <th>Actions</th>
    </tr>

    <?php while ($property = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo htmlspecialchars($property['title']); ?></td>
        <td><?php echo htmlspecialchars($property['description']); ?></td>
        <td><?php echo htmlspecialchars($property['price']); ?></td>
        <td><img src="<?php echo $property['image']; ?>" alt="Property Image" width="100"></td>
        <td>
            <a href="approve_property.php?id=<?php echo $property['id']; ?>&action=approve">Approve</a> | 
            <a href="approve_property.php?id=<?php echo $property['id']; ?>&action=reject">Reject</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>-->

<?php
//$mysqli->close();
?>
