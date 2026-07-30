<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage - Bid on Properties</title>
    <style>
body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(0, 0, 0, 0.8);
    padding: 15px 40px;
    font-size: 22px;
    font-weight: bold;
    height: 80px;
}

.project-name {
    font-size: 30px;
    font-weight: bold;
    color: #00c6ff;
}

nav {
    display: flex;
    gap: 20px;
}

nav a {
    color: white;
    text-decoration: none;
    font-size: 18px;
    padding: 12px 20px;
    background: #007bff;
    border-radius: 8px;
    transition: 0.3s;
}

nav a:hover {
    background: #0056b3;
}

.main-content {
    flex: 1;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: flex-end;
    text-align: left;
    padding: 50px;
    background-size: cover;
    background-position: center;
    transition: background 1s ease-in-out;
}

.search-box {
    background: rgba(255, 255, 255, 0.2);
    padding: 30px;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
}

.search-box input, .search-box select {
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
    width: 250px;
    background: #fff;
    color: #333;
}

.search-box input[type="number"], .search-box select {
    width: 200px;
}

.search-box input:focus, .search-box select:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

button {
    padding: 15px 25px;
    font-size: 18px;
    border: none;
    border-radius: 8px;
    background: #00c6ff;
    color: white;
    cursor: pointer;
    transition: 0.3s;
    align-self:center;
}

button:hover {
    background: #0072ff;
}
.sell-btn {
    background: #28a745;
    padding: 12px 20px;
    border-radius: 8px;
    color: white;
    text-decoration: none;
    transition: 0.3s;
}

.sell-btn:hover {
    background: #218838;
}


footer {
    background: rgba(0, 0, 0, 0.9);
    color: white;
    text-align: center;
    padding: 20px;
    height: 50px;
}
</style>
</head>
<body>
    <header>
    <script>
        const images = ['../frontend/assets/images/bc3.jpg', '../frontend/assets/images/bc.jpg', '../frontend/assets/images/bc2.jpg']; // Add your images here
        let index = 0;
        function changeBackground() {
            document.querySelector('.main-content').style.backgroundImage = `url('${images[index]}')`;
            index = (index + 1) % images.length;
        }
        setInterval(changeBackground, 3000); // Change image every 5 seconds
        window.onload = changeBackground;
</script>
       <div class="project-name">BidMyProperty</div>
        <nav>
            <?php 
            session_start();
            include '../database/db_connect.php';

            $user_type = '';
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
                $query = "SELECT user_type FROM users WHERE id='$user_id'";
                $result = mysqli_query($conn, $query);
                $user = mysqli_fetch_assoc($result);
                $user_type = ucfirst($user['user_type']);
            }
            if (isset($_SESSION['admin_id'])) {
                $user_type = 'Admin';
            }
            ?>

            <?php if (isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])): ?>
                <a href="dashboard.php">Manage Profile</a>
                <a href="view_properties.php">View Properties</a>
                <a href="convertuser.php">Role: <?php echo $user_type; ?> </a>
                
                <a href="upload_property.php">Sell</a>
                <a href="logout.php">Logout</a>
                
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </nav>
    </header>

    <div class="main-content">
        <div>
            <h2>Find Your Perfect Property</h2>
            <p>Bid on the best properties in the market..!</p>
            <div class="search-box">
                <input type="text" placeholder="Enter City">
                <h3>select Proprety Type </h3>
                <select>
                    <option>Property Type</option>
                    <option>Residential</option>
                    <option>Commercial</option>
                    <option>Agricultural</option>
                </select>
                <input type="number" placeholder="Budget">
                <button >Search</button>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 BidMyProperty. All Rights Reserved.</p>
    </footer>
</body>
</html> 
