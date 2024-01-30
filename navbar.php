<nav class="navbar">
    <div class="container">
        <div class="brand">
            <img src="img/logo.png" alt="GMR.Co" class="logo">
        </div>
        <ul class="nav-links">
            <?php if(isset($_SESSION['username'])) { ?>
                <li><a href="index.php">Home</a></li>
                <li><a href="add_product.php">Products</a></li>
                <li><a href="#">Orders</a></li>
                <li><a href="#">Analytics</a></li>
                <li>
                    <form method="post" action="logout.php">
                        <button type="submit" name="logout" class="logout-btn">Logout</button>
                    </form>
                </li>
            <?php } else { ?>
                <li><a href="login.php">Login</a></li>
            <?php } ?>
        </ul>
    </div>
</nav>
