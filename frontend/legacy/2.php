<?php
// Database connection using mysqli_connect
$servername = "localhost";
$username = "Aakib";
$password = "Aakib@1126";
$dbname = "bidmyproperty";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BidMyProperty</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <h1>BidMyProperty</h1>
        <nav>
            <a href="#login">Login</a>
            <a href="#register">Register</a>
            <a href="#listings">Listings</a>
        </nav>
    </header>
    
    <section id="register">
        <h2>Register</h2>
        <form action="register.php" method="POST">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
    </section>

    <section id="login">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </section>
    
    <section id="listings">
        <h2>Property Listings</h2>
        <div id="propertyContainer">
            <?php
            $sql = "SELECT * FROM properties";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='property'>
                        <h3>{$row['title']}</h3>
                        <p>Current Bid: $<span id='bid-{$row['id']}'>{$row['current_price']}</span></p>
                        <input type='number' id='input-{$row['id']}' placeholder='Enter bid amount'>
                        <button onclick='placeBid({$row['id']})'>Place Bid</button>
                      </div>";
            }
            ?>
        </div>
    </section>

    <script>
        function placeBid(propertyId) {
            let bidAmount = document.getElementById(`input-${propertyId}`).value;
            $.post("place_bid.php", { property_id: propertyId, bid_amount: bidAmount }, function(response) {
                if (response.success) {
                    $(`#bid-${propertyId}`).text(bidAmount);
                } else {
                    alert(response.message);
                }
            }, "json");
        }
    </script>
</body>
</html>
