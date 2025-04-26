<?php
session_start();
include 'db_connect.php';

// Get payment details from session
$payment_id = $_SESSION['current_payment_id'] ?? null;
$payment_table = $_SESSION['payment_table'] ?? null;

if (!$payment_id || !$payment_table) {
    echo "Payment information missing.";
    exit;
}

// Determine the ID field based on payment table
$id_field = ($payment_table === 'delivery_payments') ? 'delivery_id' : 'order_id';

// Query to check payment status
$query = "SELECT payment_id, $id_field, amount, payment_method, payment_status FROM $payment_table WHERE payment_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $payment_id);
$stmt->execute();
$result = $stmt->get_result();
$payment = $result->fetch_assoc();

if (!$payment) {
    echo "Payment record not found.";
    exit;
}

// If payment is successful, redirect to success page
if ($payment['payment_status'] === 'Success' && !isset($_SESSION['redirected_to_success'])) {
    $_SESSION['redirected_to_success'] = true;
    $_SESSION['last_success_payment_id'] = $payment_id;
    $_SESSION['payment_table'] = $payment_table;
    header("Location: payment_success_bill.php");
    exit;
}

// Store the order ID in a variable for display
$order_id = $payment[$id_field];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status - Savory Seasons</title>
    <meta http-equiv="refresh" content="5"> <!-- Refresh every 5 seconds -->
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .status-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 30px;
            text-align: center;
            max-width: 500px;
        }
        .status-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .status-message {
            font-size: 18px;
            margin-bottom: 20px;
            color: #666;
        }
        .payment-details {
            text-align: left;
            margin: 20px 0;
            padding: 15px;
            background-color: #f5f5f5;
            border-radius: 8px;
        }
        .payment-details p {
            margin: 10px 0;
        }
        .pending {
            color: #f39c12;
        }
    </style>
</head>
<body>
    <div class="status-container">
        <div class="status-icon">⏳</div>
        <h1>Payment Pending</h1>
        <div class="status-message">
            Your payment request has been sent and is waiting for approval.
            This page will automatically refresh to check the status.
        </div>
        
        <div class="payment-details">
            <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order_id); ?></p>
            <p><strong>Amount:</strong> ₹<?php echo htmlspecialchars($payment['amount']); ?></p>
            <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($payment['payment_method']); ?></p>
            <p><strong>Status:</strong> <span class="pending"><?php echo htmlspecialchars($payment['payment_status']); ?></span></p>
        </div>
        
        <p>Please wait... this page will refresh automatically.</p>
    </div>
</body>
</html>
