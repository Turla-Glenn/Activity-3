<?php

// Set session expiration time to 1 second
/*session_set_cookie_params(1800); // 1 second*/
session_start();

// Define database connection parameters
$host = 'localhost';          // Hostname
$username = 'root';   // MySQL username
$password = '';   // MySQL password
$database = 'gmr_db';   // Database name

// Establish database connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>