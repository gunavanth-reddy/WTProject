<?php
session_start();
include 'db_connect.php'; // database connection

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION['orderItems'])) {
    $customer_name = $_POST['name'];
    $phone = $_POST['phone']; // 📞 New phone field
    $pickup_time = $_POST['pickup_time'];
    $orderItems = $_SESSION['orderItems'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Step 1: Insert into 'orders' table (with phone)
        $stmt = $conn->prepare("INSERT INTO orders (customer_name, phone, pickup_time) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $customer_name, $phone, $pickup_time);
        $stmt->execute();
        $order_id = $stmt->insert_id;
        $_SESSION['last_order_id'] = $order_id;
        $_SESSION['order_type'] = 'takeaway';
        

        // Step 2: Prepare statements
        $stmtItem = $conn->prepare("SELECT id FROM menu WHERE item_name = ?");
        $stmtInsert = $conn->prepare("INSERT INTO order_items (order_id, item_id, quantity, price_each, price_total) VALUES (?, ?, ?, ?, ?)");

        // Step 3: Loop through order items
        foreach ($orderItems as $itemName => $item) {
            $quantity = $item['quantity'];
            $priceEach = $item['price'];
            $priceTotal = $quantity * $priceEach;

            // Get item_id from menu
            $stmtItem->bind_param("s", $itemName);
            $stmtItem->execute();
            $result = $stmtItem->get_result();

            if ($row = $result->fetch_assoc()) {
                $item_id = $row['id'];

                // Insert into order_items
                $stmtInsert->bind_param("iiidd", $order_id, $item_id, $quantity, $priceEach, $priceTotal);
                $stmtInsert->execute();
            } else {
                throw new Exception("Item '$itemName' not found in menu.");
            }
        }

        // Commit transaction and clear cart
        $conn->commit();
        //unset($_SESSION['orderItems']);

        // Redirect to payment
        header("Location: payment.php");
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        echo "Failed to process takeaway order: " . $e->getMessage();
    }
} else {
    echo "Invalid access or no items in order.";
}
?>
