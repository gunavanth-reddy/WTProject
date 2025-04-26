<?php
header("Content-Type: application/json");

// Database connection details
$host = "localhost";
$username = "root";
$password = "";
$database = "restaurant";

// Connect to MySQL
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit;
}

// Create customer_login table if it doesn't exist
$createTableSQL = "
CREATE TABLE IF NOT EXISTS customer_login (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);";

if (!$conn->query($createTableSQL)) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Failed to create customer_login table"]);
    exit;
}

// Get the raw JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Check if all required fields are present
if (
    !isset($data["name"]) || 
    !isset($data["email"]) || 
    !isset($data["phone"]) || 
    !isset($data["password"]) || 
    !isset($data["confirmPassword"])
) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit;
}

$name = trim($data["name"]);
$email = trim($data["email"]);
$phone = trim($data["phone"]);
$password = $data["password"];
$confirmPassword = $data["confirmPassword"];

// Password match validation
if ($password !== $confirmPassword) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Passwords do not match"]);
    exit;
}

// Check if the email is already registered
$checkQuery = $conn->prepare("SELECT id FROM customer_login WHERE email = ?");
$checkQuery->bind_param("s", $email);
$checkQuery->execute();
$checkQuery->store_result();

if ($checkQuery->num_rows > 0) {
    http_response_code(409);
    echo json_encode(["status" => "error", "message" => "Email already registered"]);
    $checkQuery->close();
    $conn->close();
    exit;
}
$checkQuery->close();

// Hash password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Insert user into the database
$insertQuery = $conn->prepare("INSERT INTO customer_login (name, email, phone, password) VALUES (?, ?, ?, ?)");
$insertQuery->bind_param("ssss", $name, $email, $phone, $hashedPassword);

if ($insertQuery->execute()) {
    echo json_encode(["status" => "success", "message" => "User registered successfully"]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Failed to register user"]);
}

$insertQuery->close();
$conn->close();
?>
