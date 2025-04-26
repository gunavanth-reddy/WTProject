<?php
session_start();
include 'db_connect.php';

// Get payment details from session
$payment_id = $_SESSION['last_success_payment_id'] ?? null;
$payment_table = $_SESSION['payment_table'] ?? null;

if (!$payment_id || !$payment_table) {
    echo "Payment information missing.";
    exit;
}

// Determine the ID field based on payment table
$id_field = ($payment_table === 'delivery_payments') ? 'delivery_id' : 'order_id';
$customer_table = ($payment_table === 'delivery_payments') ? 'home_delivery' : 'orders';
$customer_name_field = ($payment_table === 'delivery_payments') ? 'name' : 'customer_name';

// Query to get payment details
$query = "SELECT p.*, c.$customer_name_field as customer_name 
          FROM $payment_table p 
          JOIN $customer_table c ON p.$id_field = c.$id_field 
          WHERE p.payment_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $payment_id);
$stmt->execute();
$result = $stmt->get_result();
$payment = $result->fetch_assoc();

if (!$payment) {
    echo "Payment record not found.";
    exit;
}

// Clear the redirect flag to prevent future redirect loops
unset($_SESSION['redirected_to_success']);

// Format date
$date = new DateTime();
$formattedDate = $date->format('d M Y, h:i A');

// Generate invoice number
$invoiceNumber = 'INV-' . date('Ymd') . '-' . $payment['payment_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - Savory Seasons</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .confirmation-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 800px;
            padding: 30px;
            position: relative;
        }

        .success-banner {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            margin: -30px -30px 20px;
            font-size: 22px;
            font-weight: bold;
        }

        .success-icon {
            display: inline-block;
            width: 30px;
            height: 30px;
            background-color: white;
            border-radius: 50%;
            color: #4CAF50;
            line-height: 30px;
            margin-right: 10px;
            font-weight: bold;
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }

        .restaurant-info h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }

        .restaurant-info p {
            margin: 5px 0;
            color: #666;
        }

        .invoice-details {
            text-align: right;
        }

        .invoice-details h2 {
            margin: 0;
            color: #333;
            font-size: 18px;
        }

        .invoice-details p {
            margin: 5px 0;
            color: #666;
        }

        .customer-info {
            margin-bottom: 30px;
        }

        .customer-info h3 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 16px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        .payment-details {
            margin-bottom: 30px;
        }

        .payment-details h3 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 16px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        .payment-details p {
            margin: 10px 0;
        }

        .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #4CAF50;
            margin: 20px 0;
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            color: #777;
            font-size: 14px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        .print-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        .print-button:hover {
            background-color: #45a049;
        }

        .actions {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        @media print {
            .no-print {
                display: none;
            }
            body {
                background-color: white;
                padding: 0;
                margin: 0;
            }
            .confirmation-container {
                box-shadow: none;
                max-width: 100%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="confirmation-container">
        <div class="success-banner">
            <span class="success-icon">✓</span> Payment Successful
        </div>

        <div class="header">
            <div class="restaurant-info">
                <h1>Savory Seasons</h1>
                <p>123 Food Street, Culinary Avenue</p>
                <p>Phone: +91 98765 43210</p>
            </div>
            <div class="invoice-details">
                <h2>Payment Receipt</h2>
                <p><strong>Invoice #:</strong> <?php echo $invoiceNumber; ?></p>
                <p><strong>Date:</strong> <?php echo $formattedDate; ?></p>
            </div>
        </div>

        <div class="customer-info">
            <h3>Customer Information</h3>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($payment['customer_name']); ?></p>
            <p><strong>Order ID:</strong> <?php echo htmlspecialchars($payment[$id_field]); ?></p>
            <p><strong>Order Type:</strong> <?php echo ($payment_table === 'delivery_payments') ? 'Delivery' : 'Takeaway'; ?></p>
        </div>

        <div class="payment-details">
            <h3>Payment Details</h3>
            <p><strong>Amount:</strong> ₹<?php echo number_format($payment['amount'], 2); ?></p>
            <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($payment['payment_method']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($payment['payment_status']); ?></p>
        </div>

        <div class="total-amount">
            Total Paid: ₹<?php echo number_format($payment['amount'], 2); ?>
        </div>

        <div class="actions">
            <button class="print-button no-print" onclick="window.print()">Print Receipt</button>
        </div>

        <div class="footer">
            <p>Thank you for dining with Savory Seasons!</p>
            <p>For any inquiries, please contact us at support@savoryseasons.com</p>
        </div>
    </div>
</body>
</html>
