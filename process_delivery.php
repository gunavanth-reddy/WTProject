<?php
session_start();
include 'db_connect.php'; // database connection

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION['orderItems'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    
    // Retrieve the preferred delivery time (HH:MM format)
    $preferred_time = isset($_POST['preferred_time']) ? $_POST['preferred_time'] : null;

    // Validate and ensure time is in HH:MM format
    if ($preferred_time && preg_match("/^([01]?[0-9]|2[0-3]):([0-5][0-9])$/", $preferred_time)) {
        // Time is valid, use it as-is
        // No need to convert, the format is already in HH:MM
    } else {
        $preferred_time = null; // If the time is invalid, set it to null
    }

    $orderItems = $_SESSION['orderItems'];
    
    
    // Start the transaction
    $conn->begin_transaction();

    try {
        // Step 1: Insert into home_delivery (with time only)
        $stmt = $conn->prepare("INSERT INTO home_delivery (name, phone, email, address, preferred_time) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $phone, $email, $address, $preferred_time);
        $stmt->execute();
        $delivery_id = $stmt->insert_id;

        // Step 2: Insert order items into delivery_items table
        $stmtItem = $conn->prepare("SELECT id FROM menu WHERE item_name = ?");
        $stmtInsert = $conn->prepare("INSERT INTO delivery_items (delivery_id, item_id, quantity, price_each, total_price) VALUES (?, ?, ?, ?, ?)");

        foreach ($orderItems as $itemName => $item) {
            $quantity = $item['quantity'];
            $priceEach = $item['price'];
            $totalPrice = $quantity * $priceEach;

            // Find item_id from menu table using item_name
            $stmtItem->bind_param("s", $itemName);
            $stmtItem->execute();
            $result = $stmtItem->get_result();

            if ($row = $result->fetch_assoc()) {
                $item_id = $row['id'];

                // Insert into delivery_items table
                $stmtInsert->bind_param("iiidd", $delivery_id, $item_id, $quantity, $priceEach, $totalPrice);
                $stmtInsert->execute();
            } else {
                throw new Exception("Item '$itemName' not found in menu.");
            }
        }

        // Commit the transaction
        $conn->commit();

        // Clear the session order items
        //unset($_SESSION['orderItems']);

        $_SESSION['last_order_id'] = $delivery_id;
    $_SESSION['order_type'] = 'delivery';
        // Redirect to the payment page
        header("Location: payment.php");
        exit;

    } catch (Exception $e) {
        // Rollback the transaction in case of failure
        $conn->rollback();
        echo "Failed to process delivery: " . $e->getMessage();
    }
} else {
    echo "Invalid access or no items in order.";
}
?>
