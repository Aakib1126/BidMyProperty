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

// Fetch reports
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'];
$total_properties = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM properties"))['total'];
$total_bids = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM bids"))['total'];
$total_revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(bid_amount) AS revenue FROM bids"))['revenue'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Reports</title>
    <link rel="stylesheet" href="../frontend/css/admin.css">
</head>
<body>
    <header>
        <h1>Admin Reports</h1>
        <nav>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="admin_users.php">Manage Users</a>
            <a href="admin_properties.php">Manage Properties</a>
            <a href="admin_settings.php">Settings</a>
            <a href="admin_logout.php">Logout</a>
        </nav>
    </header>
    <section>
        <h2>Website Analytics</h2>
        <p><strong>Total Users:</strong> <?php echo $total_users; ?></p>
        <p><strong>Total Properties:</strong> <?php echo $total_properties; ?></p>
        <p><strong>Total Bids:</strong> <?php echo $total_bids; ?></p>
        <p><strong>Total Revenue from Bids:</strong> $<?php echo number_format($total_revenue, 2); ?></p>
    </section>
</body>
</html>
