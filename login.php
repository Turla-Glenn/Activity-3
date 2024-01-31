<?php
include 'config.php';

// Set a session cookie to mark the user as logged in
if (!isset($_COOKIE['login_visited'])) {
    setcookie('login_visited', true, 0, '/');
}

// Check if the session is expired
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 300)) {
    // Expire the session
    session_unset();
    session_destroy();
    // Redirect to login page
    header("Location: login.php");
    exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Check if the user is already logged in, redirect to index.php if logged in
if(isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prevent SQL Injection
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    $sql = "SELECT * FROM login WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    } else {
        $login_error = "Invalid username or password";
    }
}
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>GMR.Co</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Fontawesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
    // Function to redirect to login page if session expired
    setTimeout(function() {
        window.location.href = "logout.php";
    }, 300000); // 5 minutes in milliseconds
    </script>
</head>
<body>
<div class="container">
    <input type="checkbox" id="flip">
    <div class="cover">
        <div class="image-container">
            <img src="img/front.png" class="background-image">
            <img src="img/logofront.png" class="logo-image">
        </div>
    </div>
    <div class="forms">
        <div class="form-content">
            <div class="login-form">
                <div class="title">Login</div>
                <form method="POST">
                    <div class="input-boxes">
                        <div class="input-box">
                            <i class="fas fa-envelope"></i>
                            <input type="text" name="username" placeholder="Enter your email" required>
                        </div>
                        <div class="input-box">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" placeholder="Enter your password" required>
                        </div>
                        <div class="text"><a href="#">Forgot password?</a></div>
                        <div class="button input-box">
                            <input type="submit" value="Submit">
                        </div>
                        <?php if(isset($login_error)) { ?>
                            <div class="error"><?php echo $login_error; ?></div>
                        <?php } ?>
                        <div class="text sign-up-text">Don't have an account? <label for="flip">Sign up now</label></div>
                    </div>
                </form>
            </div>
            <div class="signup-form">
                <div class="title">Signup</div>
                <form action="#">
                    <div class="input-boxes">
                        <div class="input-box">
                            <i class="fas fa-user"></i>
                            <input type="text" placeholder="Enter your name" required>
                        </div>
                        <div class="input-box">
                            <i class="fas fa-envelope"></i>
                            <input type="text" placeholder="Enter your email" required>
                        </div>
                        <div class="input-box">
                            <i class="fas fa-lock"></i>
                            <input type="password" placeholder="Enter your password" required>
                        </div>
                        <div class="button input-box">
                            <input type="submit" value="Submit">
                        </div>
                        <div class="text sign-up-text">Already have an account? <label for="flip">Login now</label></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
    <?php include 'footer.php'; ?>
</html>