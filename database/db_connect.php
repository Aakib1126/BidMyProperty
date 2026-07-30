<?php
// Database connection
//
// IMPORTANT: these are the ORIGINAL project's credentials, kept as-is.
// If your local MySQL uses different credentials, edit the 4 lines below.
// - Fresh XAMPP/WAMP install default is usually: username "root", password "" (empty)
// - "bidmyproperty" is the database name — create it and import ../database/db.sql
//   into it via phpMyAdmin (or `mysql -u root -p bidmyproperty < db.sql`) BEFORE
//   running the app, or every page will show "Connection failed".
$servername = "localhost";
$username = "Aakib";
$password = "Aakib@1126";
$dbname = "bidmyproperty";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>