<?php
$conn = mysqli_connect("localhost", "root", "", "bidmyproperty");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $property_id = $_POST['property_id'];
    $bid_amount = $_POST['bid_amount'];

    // Get the current highest bid
    $result = mysqli_query($conn, "SELECT highest_bid FROM properties WHERE id = $property_id");
    $row = mysqli_fetch_assoc($result);
    
    if ($bid_amount > $row['highest_bid']) {
        // Update highest bid in the database
        mysqli_query($conn, "UPDATE properties SET highest_bid = $bid_amount WHERE id = $property_id");
        echo json_encode(["success" => true, "new_bid" => $bid_amount]);
    } else {
        echo json_encode(["success" => false, "message" => "Bid must be higher than the current price!"]);
    }
}
?>
