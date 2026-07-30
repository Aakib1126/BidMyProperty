<?php
session_start();
include '../database/db_connect.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_bid'])) {
    $user_id = $_SESSION['user_id'];
    $property_id = $_POST['property_id'];
    $bid_amount = $_POST['bid_amount'];
    
    // Fetch current highest bid
    $query = "SELECT highest_bid FROM properties WHERE id = '$property_id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $current_highest_bid = $row['highest_bid'];
    
    if ($bid_amount > $current_highest_bid) {
        // Update highest bid in properties table
        mysqli_query($conn, "UPDATE properties SET highest_bid = '$bid_amount' WHERE id = '$property_id'");
        
        // Insert new bid into bids table
        mysqli_query($conn, "INSERT INTO bids (user_id, property_id, bid_amount) VALUES ('$user_id', '$property_id', '$bid_amount')");
        
        echo json_encode(["success" => true, "new_bid" => $bid_amount]);
    } else {
        echo json_encode(["success" => false, "message" => "Your bid must be higher than the current highest bid."]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Bidding</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../frontend/css/styles.css">
</head>
<body>
    <h2>Property Listings</h2>
    <div id="propertyContainer">
        <?php 
        $sql = "SELECT * FROM properties";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='property'>
                    <h3>{$row['title']}</h3>
                    <p>Current Highest Bid: $<span id='bid-{$row['id']}'>{$row['highest_bid']}</span></p>
                    <input type='number' id='input-{$row['id']}' placeholder='Enter bid amount'>
                    <button onclick='placeBid({$row['id']})'>Place Bid</button>
                  </div>";
        }
        ?>
    </div>

    <script>
        function placeBid(propertyId) {
            let bidAmount = $("#input-" + propertyId).val();
            $.post("real_time_bidding.php", { property_id: propertyId, bid_amount: bidAmount, place_bid: true }, function(response) {
                let result = JSON.parse(response);
                if (result.success) {
                    $("#bid-" + propertyId).text(result.new_bid);
                } else {
                    alert(result.message);
                }
            });
        }

        function refreshBids() {
            $.get("fetch_bids.php", function(data) {
                let properties = JSON.parse(data);
                properties.forEach(property => {
                    $("#bid-" + property.id).text(property.highest_bid);
                });
            });
        }
        setInterval(refreshBids, 3000);
    </script>
</body>
</html>
