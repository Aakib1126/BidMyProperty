<?php
require_once 'config.php';

$result = mysqli_query($conn, "SELECT * FROM properties WHERE approved = '1'");
$properties = [];
while ($row = mysqli_fetch_assoc($result)) {
    $row['images'] = !empty($row['images']) ? explode(',', $row['images']) : [];
    $properties[] = $row;
}
send_json(['success' => true, 'properties' => $properties]);
