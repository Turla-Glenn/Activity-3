<?php
include 'config.php';

// Check if the session is expired
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 300)) {
    // Expire the session
    session_unset();
    session_destroy();
    // Redirect to login page
    header("Location: login.php");
    exit();
}

// Check for the existence of the login cookie
if (!isset($_COOKIE['login_visited'])) {
    // Redirect to login page
    header("Location: login.php");
    exit();
}

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GMR.Co</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
<?php include 'navbar.php'; ?>

<div class="welcome-container">
    <h1>Welcome, <?php echo $username; ?></h1>
</div>

<?php include 'footer.php'; ?>

<div class="popup" id="profile-popup">
    <div class="popup-content">
        <!-- Profile settings content goes here -->
    </div>
</div>

<script src="script.js"></script>
</body>
</html>
