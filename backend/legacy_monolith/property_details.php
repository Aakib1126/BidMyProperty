<?php
session_start();
include '../database/db_connect.php';

// Check if property ID is set in URL
if (!isset($_GET['id'])) {
    echo "<script>alert('Invalid property!'); window.location.href='all_properties.php';</script>";
    exit;
}

$property_id = $_GET['id'];

// Fetch property details
// $sql = "SELECT * FROM properties WHERE id = '$property_id' AND status = 'available'";
$sql = "SELECT * FROM properties WHERE id= '$property_id' AND approved = '1'";
$result = mysqli_query($conn, $sql);
$property = mysqli_fetch_assoc($result);

if (!$property) {
    echo "<script>alert('Property not found!'); window.location.href='all_properties.php';</script>";
    exit;
}

// Handle bid submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_bid'])) {
    $user_id = $_SESSION['user_id'];
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
        
        echo "<script>alert('Bid placed successfully!'); window.location.href='property_details.php?id=$property_id';</script>";
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
    <title><?php echo $property['title']; ?> - Property Details</title>
    <!-- <link rel="stylesheet" href="../frontend/css/styles.css"> -->
     <style>
        body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
    background: #f0f2f5;
    color: #333;
}

header {
    background: #2c3e50;
    padding: 15px 30px;
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

header h1 {
    margin: 0;
}

header nav a {
    color: #fff;
    margin-left: 15px;
    text-decoration: none;
    font-weight: 500;
}

section {
    max-width: 1200px;
    margin: 40px auto;
    background: white;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    display: flex;
    padding: 30px;
    gap: 30px;
    flex-wrap: wrap;
}

.property-images,
.property-videos {
    flex: 1 1 45%;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
}

.property-images img,
.property-videos video {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 5px 12px rgba(0, 0, 0, 0.15);
}

.property-details {
    flex: 1 1 45%;
}

.property-details h2 {
    color: #2c3e50;
    margin-top: 0;
}

.property-details p {
    font-size: 16px;
    margin: 10px 0;
}

form {
    margin-top: 20px;
}

form input[type="number"] {
    padding: 10px;
    width: 50%;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

form button {
    padding: 10px 20px;
    background-color: #27ae60;
    color: white;
    border: none;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s ease;
}

form button:hover {
    background-color: #219150;
}

@media (max-width: 768px) {
    section {
        flex-direction: column;
    }

    .property-images,
    .property-videos,
    .property-details {
        flex: 1 1 100%;
    }
}
</style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <h1>Property Details</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="propertylisting.php">Back to Listings</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php">Manage Profile</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>
    <section>
    <!-- Display Images -->
    <?php if (!empty($property['images'])) : ?>
            <?php $images = explode(",", $property['images']); ?>
            <div class="property-images">
                <?php foreach ($images as $image) : ?>
                    <img src="uploads/images/<?php echo $image; ?>" alt="Property Image" width="300">
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Display Videos -->
        <?php if (!empty($property['videos'])) : ?>
            <?php $videos = explode(",", $property['videos']); ?>
            <div class="property-videos">
                <?php foreach ($videos as $video) : ?>
                    <video width="400" controls>
                        <source src="uploads/videos/<?php echo $video; ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    <div class="property-details">
        
    <h2><?php echo $property['title']; ?></h2>
    <p><strong>Description:</strong> <?php echo $property['description']; ?></p>
    <p><strong>Price:</strong> $<?php echo $property['current_price']; ?></p>
    <p><strong>Highest Bid:</strong> $<span id="highest_bid"><?php echo $property['highest_bid']; ?></span></p>
    <p><strong>Category:</strong> <?php echo ucfirst($property['category']); ?></p>

    
        <!-- <h2>Property Title</h2> 
        <p><strong>Description:</strong> ...</p>
        <p><strong>Price:</strong> $...</p>
        <p><strong>Highest Bid:</strong> $...</p>
        <p><strong>Category:</strong> ...</p> -->

        
        <?php if (isset($_SESSION['user_id'])): ?>
            <h3>Place a Bid</h3>
            <form action="property_details.php?id=<?php echo $property_id; ?>" method="POST">
                <input type="number" name="bid_amount" placeholder="Enter bid amount" required>
                <button type="submit" name="place_bid">Place Bid</button>
            </form>
        <?php endif; ?>

    </section>       
    <script>
        function updateHighestBid() {
            $.get("fetch_highest_bid.php?id=<?php echo $property_id; ?>", function(data) {
                $("#highest_bid").text(data);
            });
        }
        setInterval(updateHighestBid, 5000); // Refresh highest bid every 5 seconds
    </script>
</body>
</html>