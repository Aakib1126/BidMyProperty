<?php
$conn = mysqli_connect("localhost", "root", "", "bidmyproperty");

$sql = "SELECT id, highest_bid FROM properties";
$result = mysqli_query($conn, $sql);

$bids = [];
while ($row = mysqli_fetch_assoc($result)) {
    $bids[] = $row;
}

echo json_encode($bids);
?>
<?php
$conn = mysqli_connect("localhost", "root", "", "bidmyproperty");

$sql = "SELECT id, highest_bid FROM properties";
$result = mysqli_query($conn, $sql);

$bids = [];
while ($row = mysqli_fetch_assoc($result)) {
    $bids[] = $row;
}

echo json_encode($bids);
?>
