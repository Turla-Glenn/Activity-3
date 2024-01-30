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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productname = $_POST['productname'];
    $price = $_POST['price'];
    $size = $_POST['size'];

    // Image upload handling
    $targetDir = "img/";
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $imageName = uniqid() . '.' . $imageFileType;

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $imageName)) {
            // Insert the product into the database
            $sql = "INSERT INTO products (productname, price, size, image) VALUES ('$productname', '$price', '$size', '$imageName')";
            mysqli_query($conn, $sql);
            // Redirect to the same page to avoid form resubmission
            header("Location: {$_SERVER['REQUEST_URI']}");
            exit(); // Prevent further execution
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "File is not an image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GMR.Co</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Additional CSS for the file input */
        .custom-file-input {
            color: transparent;
        }
        .custom-file-input::-webkit-file-upload-button {
            visibility: hidden;
        }
        .custom-file-input::before {
            content: 'Choose File';
            color: #fff;
            display: inline-block;
            background-color: #007bff;
            border: 1px solid #007bff;
            border-radius: 5px;
            padding: 8px 20px;
            cursor: pointer;
        }
        .custom-file-input:hover::before {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .custom-file-input:active::before {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
<!--     <script>
    // Function to redirect to logout page when tab is closed
    window.addEventListener("beforeunload", function (event) {
        // Check if the tab is being closed
        if (event.clientX < 0 && event.clientY < 0) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "logout.php", true);
            xhr.send();
        }
    });

    // Function to redirect to logout page after 5 minutes of inactivity
    function checkInactive() {
        setTimeout(function () {
            window.location.href = "logout.php";
        }, 300000); // 5 minutes in milliseconds
    }

    // Add event listeners for user activity
    document.addEventListener("mousemove", resetInactiveTimer);
    document.addEventListener("keydown", resetInactiveTimer);

    // Reset the inactive timer on user activity
    function resetInactiveTimer() {
        clearTimeout(checkInactive());
        checkInactive();
    }
    </script> -->
</head>
<body>
    <?php include 'navbar.php'; ?>

    <!-- Adding Product Section -->
    <div class="container add-product-container">
        <h2>Add Product</h2>
        <form method="post" enctype="multipart/form-data" class="add-product-form">
            <div class="form-group">
                <label for="productname">Product Name:</label>
                <input type="text" id="productname" name="productname" required>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" step="0.01" min="0" required>
            </div>
            <div class="form-group">
                <label for="size">Size:</label>
                <select id="size" name="size">
                    <option value="small">Small</option>
                    <option value="medium">Medium</option>
                    <option value="large">Large</option>
                    <option value="super">Super Bigger Size</option>
                </select>
            </div>
            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" id="image" name="image" accept="image/*" class="custom-file-input" required>
            </div>
            <button type="submit">Add Product</button>
        </form>
    </div>
    <!-- End of Adding Product Section -->

    <!-- Display added products -->
    <div class="added-products">
        <h2>Added Products</h2>
        <div class="product-grid">
            <?php
            // Fetch added products from the database and display them
            $sql = "SELECT * FROM products";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="product-item">';
                echo '<img src="img/' . $row['image'] . '" alt="' . $row['productname'] . '" class="product-image">';
                echo '<div class="product-details">';
                echo '<h3>' . $row['productname'] . '</h3>';
                echo '<p>Price: $' . $row['price'] . '</p>';
                echo '<p>Size: ' . $row['size'] . '</p>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="script.js"></script>
</body>
</html>