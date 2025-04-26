<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "restaurant";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
// Optional: Uncomment the line below to confirm successful connection (for testing only)
//echo "✅ Database connected successfully!";


// Optional: Uncomment the line below to confirm successful connection (for testing only)
// echo "Database connected successfully.";
?>
