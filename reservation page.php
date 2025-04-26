<?php
// Database connection settings
$host = 'localhost'; // Update with your database host
$dbname = 'restaurant'; // Update with your database name
$username = 'root'; // Update with your database username
$password = ''; // Update with your database password

// CORS headers (for testing purposes)
header('Access-Control-Allow-Origin: *'); // Allow all origins (use specific domain in production)
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Response array
$response = array('status' => '', 'message' => '');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // Check if request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }
    
    // Create a new PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the reservations table exists, if not create it
    $tableExistsQuery = $pdo->query("SHOW TABLES LIKE 'reservations'");
    if ($tableExistsQuery->rowCount() == 0) {
        $createTableSQL = "CREATE TABLE IF NOT EXISTS `reservations` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(100) NOT NULL,
            `email` varchar(100) NOT NULL,
            `phone` varchar(20) NOT NULL,
            `reservation_date` date NOT NULL,
            `reservation_time` time NOT NULL,
            `guests` int(11) NOT NULL,
            `created_at` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $pdo->exec($createTableSQL);
    }

    // Get form data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $reservation_date = trim($_POST['date'] ?? '');
    $reservation_time = trim($_POST['time'] ?? '');
    $guests = (int)($_POST['guests'] ?? 0);

    // Validate required fields
    if (empty($name) || empty($email) || empty($phone) || empty($reservation_date) || empty($reservation_time) || empty($guests)) {
        throw new Exception('All fields are required.');
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format.');
    }

    // Validate number of guests
    if ($guests < 1 || $guests > 20) {
        throw new Exception('Number of guests must be between 1 and 20.');
    }
    
    // Validate reservation date (must be current or future date)
    $current_date = date('Y-m-d');
    if ($reservation_date < $current_date) {
        throw new Exception('Reservation date must be today or a future date.');
    }

    // Validate reservation time (must be between 11:00 and 22:00)
    if ($reservation_time < '11:00' || $reservation_time > '22:00') {
        throw new Exception('Reservation time must be between 11:00 AM and 10:00 PM.');
    }

    // Prepare the SQL statement
    $sql = "INSERT INTO reservations (name, email, phone, reservation_date, reservation_time, guests, created_at) 
            VALUES (:name, :email, :phone, :reservation_date, :reservation_time, :guests, NOW())";
    
    $stmt = $pdo->prepare($sql);
    
    // Bind parameters
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':reservation_date', $reservation_date);
    $stmt->bindParam(':reservation_time', $reservation_time);
    $stmt->bindParam(':guests', $guests, PDO::PARAM_INT);

    // Execute the statement
    $stmt->execute();

    // Success response
    $response['status'] = 'success';
    $response['message'] = 'Reservation submitted successfully!';

} catch (PDOException $e) {
    // Database error response
    $response['status'] = 'error';
    $response['message'] = 'Database error: ' . $e->getMessage();
    
    // Log the error for debugging
    error_log('Database Error: ' . $e->getMessage());
} catch (Exception $e) {
    // General error response
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
    
    // Log the error for debugging
    error_log('General Error: ' . $e->getMessage());
}

// Output the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
