<?php
require_once 'config.php';

$result = mysqli_query($conn, "SELECT id, highest_bid FROM properties");
$bids = [];
while ($row = mysqli_fetch_assoc($result)) {
    $bids[] = $row;
}
send_json($bids);
