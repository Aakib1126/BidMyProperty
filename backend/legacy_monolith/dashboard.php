<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include '../database/db_connect.php';

$user_id = $_SESSION['user_id'];

// Fetch bid history
$bid_query = "SELECT b.bid_amount, p.title, b.bid_time, 
              CASE WHEN b.bid_amount = p.highest_bid THEN 'Winning' ELSE 'Outbid' END AS status
              FROM bids b JOIN properties p ON b.property_id = p.id WHERE b.user_id = $user_id";
$bid_result = mysqli_query($conn, $bid_query);

// Fetch watchlist
$watchlist_query = "SELECT p.title, p.current_price FROM watchlist w JOIN properties p ON w.property_id = p.id WHERE w.user_id = $user_id";
$watchlist_result = mysqli_query($conn, $watchlist_query);

// Fetch user profile details
$user_query = "SELECT name, email FROM users WHERE id = $user_id";
$user_result = mysqli_fetch_assoc(mysqli_query($conn, $user_query));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
   
     <style>
    
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', sans-serif;
    background-color: #f4f6f8;
    color: #333;
    line-height: 1.6;
    padding: 0 20px;
}

header {
    background-color: #2c3e50;
    color: #fff;
    padding: 20px;
    margin-bottom: 30px;
    text-align: center;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

header h1 {
    margin-bottom: 10px;
}

nav a {
    color: #ecf0f1;
    margin: 0 15px;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s;
}

nav a:hover {
    color: #1abc9c;
}

/* Section Styles */
section {
    background: #ffffff;
    padding: 25px 30px;
    border-radius: 10px;
    margin-bottom: 30px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.07);
}

section h2 {
    margin-bottom: 15px;
    color: #2c3e50;
    border-bottom: 2px solid #eee;
    padding-bottom: 8px;
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

table th,
table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #e2e2e2;
}

table th {
    background-color: #ecf0f1;
    font-weight: 600;
    color: #2c3e50;
}

table tr:hover {
    background-color: #f9f9f9;
}

/* Responsive */
@media screen and (max-width: 768px) {
    table, thead, tbody, th, td, tr {
        display: block;
    }

    table thead {
        display: none;
    }

    table tr {
        margin-bottom: 15px;
    }

    table td {
        padding-left: 50%;
        position: relative;
    }

    table td::before {
        position: absolute;
        left: 15px;
        top: 12px;
        white-space: nowrap;
        font-weight: bold;
        color: #555;
    }

    table td:nth-child(1)::before { content: "Property"; }
    table td:nth-child(2)::before { content: "Bid Amount / Current Price"; }
    table td:nth-child(3)::before { content: "Time"; }
    table td:nth-child(4)::before { content: "Status"; }
}
</style>
</head>
<body>
    <header>
        <h1>User Dashboard</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <section>
        <h2>Profile Management</h2>
        <p><strong>Name:</strong> <?php echo $user_result['name']; ?></p>
        <p><strong>Email:</strong> <?php echo $user_result['email']; ?></p>
    </section>

    <section>
        <h2>Bid History & Status</h2>
        <table>
            <tr><th>Property</th><th>Bid Amount</th><th>Time</th><th>Status</th></tr>
            <?php while ($row = mysqli_fetch_assoc($bid_result)) : ?>
                <tr>
                    <td><?php echo $row['title']; ?></td>
                    <td>$<?php echo $row['bid_amount']; ?></td>
                    <td><?php echo $row['bid_time']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </section>

    <section>
        <h2>Property Watchlist</h2>
        <table>
            <tr><th>Property</th><th>Current Price</th></tr>
            <?php while ($row = mysqli_fetch_assoc($watchlist_result)) : ?>
                <tr>
                    <td><?php echo $row['title']; ?></td>
                    <td>$<?php echo $row['current_price']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </section>
</body>
</html>
