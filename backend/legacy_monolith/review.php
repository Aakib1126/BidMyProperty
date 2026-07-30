<?php
include '../database/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $rating = intval($_POST["rating"]);
    $message = mysqli_real_escape_string($conn, $_POST["message"]);

    $sql = "INSERT INTO reviews (name, email, rating, message) VALUES ('$name', '$email', '$rating', '$message')";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Review submitted successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}

$reviews = mysqli_query($conn, "SELECT * FROM reviews ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews & Feedback</title>
    <style>/* General Styling */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    text-align: center;
}

/* Header */
header {
    background-color: #007bff;
    color: white;
    padding: 15px 0;
}

header h1 {
    margin: 0;
}

nav a {
    color: white;
    text-decoration: none;
    margin: 0 15px;
    font-size: 18px;
    transition: 0.3s;
}

nav a:hover {
    color: #ffcc00;
}

/* Review Section */
.review-section {
    background: white;
    padding: 30px;
    width: 50%;
    margin: 20px auto;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

.review-section h2 {
    color: #007bff;
}

.review-section form {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.review-section input, 
.review-section select, 
.review-section textarea {
    padding: 10px;
    font-size: 16px;
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.review-section button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 12px;
    cursor: pointer;
    font-size: 18px;
    border-radius: 5px;
    transition: 0.3s;
}

.review-section button:hover {
    background-color: #0056b3;
}

/* Display Reviews */
.display-reviews {
    margin: 30px auto;
    width: 60%;
    text-align: left;
}

.display-reviews h2 {
    color: #007bff;
    text-align: center;
}

.review-box {
    background: white;
    padding: 20px;
    margin-bottom: 15px;
    border-radius: 8px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}

.review-box h3 {
    margin: 0;
    color: #333;
}

.rating {
    color: #f39c12;
    font-size: 18px;
}

.date {
    display: block;
    margin-top: 10px;
    color: #888;
}
</style>
</head>
<body>

<header>
    <h1>Customer Reviews</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="feedback.php">Reviews</a>
    </nav>
</header>

<section class="review-section">
    <h2>Leave a Review</h2>
    <form action="feedback.php" method="POST">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <select name="rating" required>
            <option value="">Rate Us</option>
            <option value="5">⭐⭐⭐⭐⭐ (Excellent)</option>
            <option value="4">⭐⭐⭐⭐ (Good)</option>
            <option value="3">⭐⭐⭐ (Average)</option>
            <option value="2">⭐⭐ (Poor)</option>
            <option value="1">⭐ (Bad)</option>
        </select>
        <textarea name="message" placeholder="Write your feedback here..." required></textarea>
        <button type="submit">Submit Review</button>
    </form>
</section>

<section class="display-reviews">
    <h2>What Our Customers Say</h2>
    <?php while ($review = mysqli_fetch_assoc($reviews)) : ?>
        <div class="review-box">
            <h3><?php echo $review["name"]; ?></h3>
            <p class="rating">Rating: <?php echo str_repeat("⭐", $review["rating"]); ?></p>
            <p><?php echo $review["message"]; ?></p>
            <span class="date"><?php echo date("F j, Y", strtotime($review["created_at"])); ?></span>
        </div>
    <?php endwhile; ?>
</section>

</body>
</html>
