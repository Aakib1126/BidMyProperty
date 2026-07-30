<?php
session_start();
include '../database/db_connect.php';

// Check if admin is logged in (Optional)
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Approve property
if (isset($_GET['approve'])) {
    $id = $_GET['approve'];
    mysqli_query($conn, "UPDATE properties SET approved = 1 WHERE id = $id");
    header("Location: admin_properties.php");
    exit;
}

// Reject/delete property
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM properties WHERE id = $id");
    header("Location: admin_properties.php");
    exit;
}

// Get unapproved properties
$result = mysqli_query($conn, "SELECT * FROM properties WHERE approved = 0");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Approval</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
        }
        a.button {
            padding: 6px 12px;
            text-decoration: none;
            background-color: #28a745;
            color: white;
            border-radius: 4px;
        }
        a.delete {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <h2>Pending Property Approvals</h2>
    <table>
        <tr>
            <th>Title</th>
            <th>Owner</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row['title'] ?></td>
            <td><?= $row['id'] ?></td>
            <td>$<?= $row['current_price'] ?></td>
            <td>
                <a class="button" href="?approve=<?= $row['id'] ?>">Approve</a>
                <a class="button delete" href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
