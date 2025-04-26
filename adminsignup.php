<?php
// MySQL connection settings
$host = "localhost";
$username = "root";
$password = ""; // Your MySQL root password
$dbname = "restaurant";

// Connect to MySQL server
$conn = new mysqli($host, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql_create_db = "CREATE DATABASE IF NOT EXISTS $dbname";
if (!$conn->query($sql_create_db)) {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// Create table if it doesn't exist
$sql_create_table = "
CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL
)";
if (!$conn->query($sql_create_table)) {
    die("Error creating table: " . $conn->error);
}

// Get data from POST request
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$raw_password = $_POST['password'] ?? '';

// Basic validation
if (empty($name) || empty($email) || empty($raw_password)) {
    echo "All fields are required.";
    exit();
}

if (strlen($raw_password) < 6) {
    echo "Password must be at least 6 characters.";
    exit();
}

// Hash the password
$hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);

// Check if email already exists
$sql_check = "SELECT * FROM admins WHERE email = ?";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    echo "Admin already exists with this email.";
    $stmt->close();
    $conn->close();
    exit();
}
$stmt->close();

// Insert new admin
$sql_insert = "INSERT INTO admins (name, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql_insert);
$stmt->bind_param("sss", $name, $email, $hashed_password);

if ($stmt->execute()) {
    // Redirect after successful signup
    header("Location: adminlogin.html");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
