<?php
session_start();
include '../database/db_connect.php';

// Fetch all available properties
$sql = "SELECT * FROM properties WHERE status = 'available'";
$result = mysqli_query($conn, $sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_bid'])) {
    $user_id = $_SESSION['user_id'];
    $property_id = $_POST['property_id'];
    $bid_amount = $_POST['bid_amount'];
    
    // Get current highest bid
    $query = "SELECT highest_bid FROM properties WHERE id = '$property_id'";
    $res = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($res);
    $current_highest_bid = $row['highest_bid'];
    
    if ($bid_amount > $current_highest_bid) {
        // Update highest bid in properties table
        mysqli_query($conn, "UPDATE properties SET highest_bid = '$bid_amount' WHERE id = '$property_id'");
        
        // Insert new bid into bids table
        mysqli_query($conn, "INSERT INTO bids (user_id, property_id, bid_amount) VALUES ('$user_id', '$property_id', '$bid_amount')");
        
        echo "<script>alert('Bid placed successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Your bid must be higher than the current highest bid.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage - Bid on Properties</title>
    <link rel="stylesheet" href="../frontend/css/styles.css">
</head>
<body>
    <h2>Available Properties for Bidding</h2>
    <div class="property-list">
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <div class="property">
                <h3><?php echo $row['title']; ?></h3>
                <p><?php echo $row['description']; ?></p>
                <p><strong>Current Highest Bid:</strong> $<?php echo $row['highest_bid']; ?></p>
                <form action="index.php" method="POST">
                    <input type="hidden" name="property_id" value="<?php echo $row['id']; ?>">
                    <input type="number" name="bid_amount" placeholder="Enter bid amount" required>
                    <button type="submit" name="place_bid">Place Bid</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
