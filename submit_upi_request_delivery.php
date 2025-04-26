<?php
session_start();
include 'db_connect.php'; // database connection

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['amount'])) {
    $amount = $_POST['amount'];
    $delivery_id = $_SESSION['last_order_id'] ?? $_POST['order_id'] ?? null;

    if (!$delivery_id) {
        echo "Delivery ID is missing.";
        exit;
    }

    // Insert payment information into delivery_payments table
    $stmt = $conn->prepare("INSERT INTO delivery_payments (delivery_id, amount, payment_method, payment_status) VALUES (?, ?, 'UPI', 'Pending')");
    $stmt->bind_param("id", $delivery_id, $amount);

    if ($stmt->execute()) {
        $payment_id = $conn->insert_id; // Get the newly inserted payment ID
        $_SESSION['current_payment_id'] = $payment_id;
        $_SESSION['payment_table'] = 'delivery_payments';
        echo "Delivery payment recorded successfully!";
        header("Location: payment_status.php");
        exit;
    } else {
        echo "Failed to record delivery payment: " . $conn->error;
    }

} else {
    echo "Invalid request or missing amount.";
}
?>
