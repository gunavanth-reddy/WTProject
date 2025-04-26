<?php
// Database connection settings
$host = "localhost";
$username = "root";
$password = ""; // Change to your actual MySQL password
$dbname = "restaurant";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get email and password from form
$email = $_POST['email'] ?? '';
$raw_password = $_POST['password'] ?? '';

// Validate input
if (empty($email) || empty($raw_password)) {
    echo "Please enter both email and password.";
    exit();
}

// Prepare SQL to fetch the admin
$sql = "SELECT * FROM admins WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $admin = $result->fetch_assoc();

    if (password_verify($raw_password, $admin['password'])) {
        // Password is correct - redirect to dashboard
        header("Location: admindash.html");
        exit();
    } else {
        echo "Incorrect password.";
    }
} else {
    echo "Admin not found.";
}

$stmt->close();
$conn->close();
?>
