<?php
session_start();
include 'db_connect.php'; // database connection

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['amount'])) {
    $amount = $_POST['amount'];
    $order_id = $_SESSION['last_order_id'] ?? $_POST['order_id'] ?? null;

    if (!$order_id) {
        echo "Order ID is missing.";
        exit;
    }

    // Insert payment information into takeaway_payments table
    $stmt = $conn->prepare("INSERT INTO takeaway_payments (order_id, amount, payment_method, payment_status) VALUES (?, ?, 'UPI', 'Pending')");
    $stmt->bind_param("id", $order_id, $amount);

    if ($stmt->execute()) {
        $payment_id = $conn->insert_id; // Get the newly inserted payment ID
        $_SESSION['current_payment_id'] = $payment_id;
        $_SESSION['payment_table'] = 'takeaway_payments';
        echo "Takeaway payment recorded successfully!";
        header("Location: payment_status.php");
        exit;
    } else {
        echo "Failed to record takeaway payment: " . $conn->error;
    }
} else {
    echo "Invalid request or missing amount.";
}
?>
