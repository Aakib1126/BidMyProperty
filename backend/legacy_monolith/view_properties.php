<?php
session_start();
include '../database/db_connect.php';

// Fetch all approved properties
// $sql = "SELECT * FROM properties WHERE status = 'available'";
$sql = "SELECT * FROM properties WHERE  approved = '1'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Properties</title>
    <style>
        /* General Page Styling */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f5f5f5;
    color: #333;
    margin: 0;
    padding: 0;
}

/* Header Styling */
header {
    background: #2c3e50;
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

header h1 {
    margin: 0;
    font-size: 24px;
}

/* Navigation Styling */
nav a {
    color: white;
    text-decoration: none;
    font-size: 16px;
    padding: 10px 15px;
    background: #3498db;
    border-radius: 5px;
    transition: 0.3s;
}

nav a:hover {
    background: #2980b9;
}

/* Property Listings Section */
section {
    max-width: 1200px;
    margin: 30px auto;
    padding: 20px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Horizontal Scroll Container */
.property-list {
    display: flex;
    overflow-x: auto;
    gap: 20px;
    padding-bottom: 10px;
    scroll-snap-type: x mandatory;
    scrollbar-width: thin;
}

/* Scrollbar Styling */
.property-list::-webkit-scrollbar {
    height: 8px;
}

.property-list::-webkit-scrollbar-thumb {
    background: #3498db;
    border-radius: 10px;
}

.property-list::-webkit-scrollbar-track {
    background: #ddd;
    border-radius: 10px;
}

/* Individual Property Card */
.property {
    background: #ffffff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s;
    min-width: 300px;
    scroll-snap-align: start;
}

.property:hover {
    transform: translateY(-5px);
}

/* Property Image */
.property img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 5px;
    margin-bottom: 10px;
}

/* Property Title */
.property h3 a {
    text-decoration: none;
    color: #2c3e50;
    font-size: 20px;
}

.property h3 a:hover {
    color: #3498db;
}

/* Price & Highest Bid */
.property p {
    font-size: 16px;
    color: #666;
    margin: 5px 0;
}

.property strong {
    color: #2c3e50;
    font-weight: bold;
}

/* Responsive Design */
@media (max-width: 768px) {
    section {
        width: 90%;
    }

    .property {
        min-width: 250px;
    }
}

    </style>
</head>
<body>
    <header>
        <h1>All Properties</h1>
        <nav>
            <a href="index.php">Home</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php">Manage Profile</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>

    <section>
        <h2>Property Listings</h2>
        <div class="property-list">
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <div class="property">
                    <h3><a href="property_details.php?id=<?php echo $row['id']; ?>"> <?php echo $row['title']; ?> </a></h3>
                    <p><strong>Price:</strong> $<?php echo $row['current_price']; ?></p>
                    <p><strong>Highest Bid:</strong> $<?php echo $row['highest_bid']; ?></p>
                    
                    <!-- Display Thumbnail Image -->
                    <?php if (!empty($row['images'])) : ?>
                        <?php $images = explode(",", $row['images']); ?>
                        <img src="uploads/images/<?php echo $images[0]; ?>" alt="Property Image" width="200">
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
</body>
</html>
