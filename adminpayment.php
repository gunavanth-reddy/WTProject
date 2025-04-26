<?php
session_start();
include 'db_connect.php';

// Uncomment this block when deploying to require admin login
/*
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit;
}
*/

// Process payment status update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['payment_id']) && isset($_POST['action'])) {
    $payment_id = $_POST['payment_id'];
    $table = $_POST['payment_table'];
    $action = $_POST['action'];
    $status = ($action === 'accept') ? 'Success' : 'Declined';

    $stmt = $conn->prepare("UPDATE $table SET payment_status = ? WHERE payment_id = ?");
    $stmt->bind_param("si", $status, $payment_id);

    if ($stmt->execute()) {
        $statusMessage = "Payment " . ($status === 'Success' ? 'accepted' : 'declined') . " successfully.";
        // Remove the redirection code below
        // if ($status === 'Success') {
        //     $_SESSION['last_success_payment_id'] = $payment_id;
        //     $_SESSION['payment_table'] = $table;
        //     header("Location: payment_success_bill.php");
        //     exit;
        // }
    } else {
        $statusMessage = "Error updating payment status: " . $conn->error;
    }
    
}

// Store payments and errors
$payments = [];
$errors = [];

// Fetch delivery payments
try {
    $deliveryQuery = "SELECT dp.payment_id, dp.delivery_id, dp.amount, dp.payment_method, dp.payment_status, 
                     hd.name as customer_name, CURRENT_TIMESTAMP as created_at 
                     FROM delivery_payments dp
                     JOIN home_delivery hd ON dp.delivery_id = hd.delivery_id
                     ORDER BY dp.payment_id DESC";
    $deliveryResult = $conn->query($deliveryQuery);

    if ($deliveryResult) {
        while ($row = $deliveryResult->fetch_assoc()) {
            $row['payment_table'] = 'delivery_payments';
            $payments[] = $row;
        }
    }
} catch (Exception $e) {
    $errors[] = "Error fetching delivery payments: " . $e->getMessage();
}

// Fetch takeaway payments
try {
    $takeawayQuery = "SELECT tp.payment_id, tp.order_id, tp.amount, tp.payment_method, tp.payment_status, 
                     o.customer_name as customer_name, CURRENT_TIMESTAMP as created_at 
                     FROM takeaway_payments tp
                     JOIN orders o ON tp.order_id = o.order_id
                     ORDER BY tp.payment_id DESC";
    $takeawayResult = $conn->query($takeawayQuery);

    if ($takeawayResult) {
        while ($row = $takeawayResult->fetch_assoc()) {
            $row['payment_table'] = 'takeaway_payments';
            $payments[] = $row;
        }
    }
} catch (Exception $e) {
    $errors[] = "Error fetching takeaway payments: " . $e->getMessage();
}

// Sort by newest
if (!empty($payments)) {
    usort($payments, function($a, $b) {
        return $b['payment_id'] - $a['payment_id'];
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Payments</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Your CSS goes here -->
    <style>
        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #f4f4f4;
        }

        /* Sidebar */
        .sidebar {
            width: 220px;
            background: #2c3e50;
            color: white;
            display: flex;
            flex-direction: column;
            padding: 20px 10px;
        }

        .sidebar h2 {
            font-size: 22px;
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar a {
            text-decoration: none;
            color: white;
            font-size: 18px;
            padding: 12px 20px;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: #34495e;
            padding-left: 25px;
        }

        .sidebar a.active {
            background: #1abc9c;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .header {
            background: #3498db;
            color: white;
            padding: 15px;
            font-size: 24px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .orders-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #2c3e50;
            color: white;
        }

        tr:hover {
            background: #f1f1f1;
        }

        /* Status colors */
        .status-pending {
            color: #f39c12;
            font-weight: bold;
        }
        
        .status-success {
            color: #27ae60;
            font-weight: bold;
        }
        
        .status-declined {
            color: #e74c3c;
            font-weight: bold;
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-accept {
            background-color: #27ae60;
            color: white;
        }

        .btn-decline {
            background-color: #e74c3c;
            color: white;
        }

        .btn:hover {
            opacity: 0.8;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 400px;
            border-radius: 8px;
            text-align: center;
        }

        .modal-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        /* Status message */
        .status-message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: center;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 180px;
            }

            th, td {
                padding: 10px;
            }
            
            .modal-content {
                width: 90%;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="admindash.php">Home</a>
    <a href="adminmenu.html">Menu & Items</a>
    <a href="adminpayment.php" class="active">Payments</a>
    <a href="adminlogin.html" onclick="logout()">Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="header">
        <h1>Admin Payments</h1>
    </div>

    <?php if (isset($statusMessage)): ?>
        <div class="status-message <?php echo strpos($statusMessage, 'Error') !== false ? 'error-message' : 'success-message'; ?>">
            <?php echo $statusMessage; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $error): ?>
            <div class="status-message error-message"><?php echo $error; ?></div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="orders-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer Name</th>
                    <th>Order Type</th>
                    <th>Payment Method</th>
                    <th>Amount (₹)</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($payments)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center;">No payments found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td><?php echo isset($payment['delivery_id']) ? $payment['delivery_id'] : $payment['order_id']; ?></td>
                            <td><?php echo htmlspecialchars($payment['customer_name']); ?></td>
                            <td><?php echo isset($payment['delivery_id']) ? 'Delivery' : 'Takeaway'; ?></td>
                            <td><?php echo htmlspecialchars($payment['payment_method']); ?></td>
                            <td>₹<?php echo number_format($payment['amount'], 2); ?></td>
                            <td class="status-<?php echo strtolower($payment['payment_status']); ?>">
                                <?php echo htmlspecialchars($payment['payment_status']); ?>
                            </td>
                            <td><?php echo date('Y-m-d H:i', strtotime($payment['created_at'])); ?></td>
                            <td>
                                <?php if ($payment['payment_status'] === 'Pending'): ?>
                                    <div class="action-buttons">
                                        <button class="btn btn-accept" onclick="showModal('accept', <?php echo $payment['payment_id']; ?>, '<?php echo $payment['payment_table']; ?>')">Accept</button>
                                        <button class="btn btn-decline" onclick="showModal('decline', <?php echo $payment['payment_id']; ?>, '<?php echo $payment['payment_table']; ?>')">Decline</button>
                                    </div>
                                <?php else: ?>
                                    <span>No actions available</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="modal">
    <div class="modal-content">
        <h3 id="modalTitle">Confirm Action</h3>
        <p id="modalMessage">Are you sure you want to proceed with this action?</p>
        <div class="modal-buttons">
            <form method="POST" action="">
                <input type="hidden" id="payment_id" name="payment_id" value="">
                <input type="hidden" id="payment_table" name="payment_table" value="">
                <input type="hidden" id="action" name="action" value="">
                <button type="button" class="btn" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-accept" id="confirmButton">Confirm</button>
            </form>
        </div>
    </div>
</div>

<script>
    function showModal(action, paymentId, paymentTable) {
        const modal = document.getElementById('confirmModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalMessage = document.getElementById('modalMessage');
        const confirmButton = document.getElementById('confirmButton');
        const actionInput = document.getElementById('action');
        const paymentIdInput = document.getElementById('payment_id');
        const paymentTableInput = document.getElementById('payment_table');

        if (action === 'accept') {
            modalTitle.textContent = 'Accept Payment';
            modalMessage.textContent = 'Are you sure you want to accept this payment?';
            confirmButton.className = 'btn btn-accept';
        } else {
            modalTitle.textContent = 'Decline Payment';
            modalMessage.textContent = 'Are you sure you want to decline this payment?';
            confirmButton.className = 'btn btn-decline';
        }

        actionInput.value = action;
        paymentIdInput.value = paymentId;
        paymentTableInput.value = paymentTable;

        modal.style.display = 'block';
    }

    function closeModal() {
        document.getElementById('confirmModal').style.display = 'none';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('confirmModal');
        if (event.target === modal) {
            closeModal();
        }
    }

    function logout() {
        window.location.href = 'adminlogin.html';
    }
</script>

</body>
</html>

