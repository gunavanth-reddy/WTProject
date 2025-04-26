<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restaurant";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create DB if not exists
$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);

// Create `menu` table with availability column
$sql = "CREATE TABLE IF NOT EXISTS menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(100),
    category VARCHAR(50),
    price DECIMAL(6,2),
    availability BOOLEAN DEFAULT TRUE
)";
if ($conn->query($sql) === TRUE) {
    echo "Table 'menu' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

// Check if items already exist
$check = $conn->query("SELECT COUNT(*) AS count FROM menu");
$row = $check->fetch_assoc();

if ($row['count'] == 0) {
    $sql_insert = "
        INSERT INTO menu (item_name, category, price, availability) VALUES
        -- Food Items
        ('Butter Chicken', 'food', 300.00, TRUE),
        ('Paneer Tikka', 'food', 250.00, TRUE),
        ('Garlic Naan', 'food', 50.00, TRUE),
        ('Chicken Biryani', 'food', 300.00, TRUE),
        ('Dal Makhani', 'food', 180.00, TRUE),
        ('Chole Bhature', 'food', 220.00, TRUE),
        ('Palak Paneer', 'food', 200.00, TRUE),
        ('Masala Dosa', 'food', 120.00, TRUE),

        -- Drink Items
        ('Masala Chai', 'beverage', 80.00, TRUE),
        ('Mango Lassi', 'beverage', 75.00, TRUE),
        ('Sweet Lassi', 'beverage', 65.00, TRUE),
        ('Badam Milk', 'beverage', 90.00, TRUE),
        ('Thandai', 'beverage', 85.00, TRUE),
        ('Coffee', 'beverage', 50.00, TRUE)
    ";
    if ($conn->query($sql_insert) === TRUE) {
        echo "Menu items inserted successfully.";
    } else {
        echo "Error inserting items: " . $conn->error;
    }
} else {
    // Add availability column if it doesn't exist
    $check_column = $conn->query("SHOW COLUMNS FROM menu LIKE 'availability'");
    if ($check_column->num_rows == 0) {
        $alter_table = "ALTER TABLE menu ADD COLUMN availability BOOLEAN DEFAULT TRUE";
        if ($conn->query($alter_table) === TRUE) {
            echo "Added availability column to existing table.";
        } else {
            echo "Error adding column: " . $conn->error;
        }
    } else {
        echo "Items already exist. Skipping insertion.";
    }
}

$conn->close();
?>
