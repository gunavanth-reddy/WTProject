<?php
session_start();
include 'db_connect.php';

// Check if admin is logged in
/*if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.html");
    exit;
}*/

// Get order counts
$deliveryOrderQuery = "SELECT COUNT(*) as delivery_count FROM delivery_payments";
$takeawayOrderQuery = "SELECT COUNT(*) as takeaway_count FROM takeaway_payments";

$deliveryResult = $conn->query($deliveryOrderQuery);
$takeawayResult = $conn->query($takeawayOrderQuery);

$deliveryCount = $deliveryResult->fetch_assoc()['delivery_count'] ?? 0;
$takeawayCount = $takeawayResult->fetch_assoc()['takeaway_count'] ?? 0;
$totalOrders = $deliveryCount + $takeawayCount;

// Get menu item count
$menuQuery = "SELECT COUNT(*) as total_items FROM menu";
$menuResult = $conn->query($menuQuery);
$menuCount = $menuResult->fetch_assoc()['total_items'] ?? 0;

// Get total payments
$deliveryPaymentQuery = "SELECT SUM(amount) as delivery_amount FROM delivery_payments WHERE payment_status = 'Success'";
$takeawayPaymentQuery = "SELECT SUM(amount) as takeaway_amount FROM takeaway_payments WHERE payment_status = 'Success'";

$deliveryPaymentResult = $conn->query($deliveryPaymentQuery);
$takeawayPaymentResult = $conn->query($takeawayPaymentQuery);

$deliveryAmount = $deliveryPaymentResult->fetch_assoc()['delivery_amount'] ?? 0;
$takeawayAmount = $takeawayPaymentResult->fetch_assoc()['takeaway_amount'] ?? 0;
$totalPayments = $deliveryAmount + $takeawayAmount;

// Get recent orders (both delivery and takeaway)
$recentOrdersQuery = "
    (SELECT 
        'Delivery' as order_type,
        dp.payment_id,
        hd.name as customer_name,
        hd.phone,
        dp.amount,
        dp.payment_status,
        dp.created_at
     FROM delivery_payments dp
     JOIN home_delivery hd ON dp.delivery_id = hd.delivery_id
     ORDER BY dp.created_at DESC
     LIMIT 5)
    
    UNION ALL
    
    (SELECT 
        'Takeaway' as order_type,
        tp.payment_id,
        o.customer_name,
        o.phone,
        tp.amount,
        tp.payment_status,
        tp.created_at
     FROM takeaway_payments tp
     JOIN orders o ON tp.order_id = o.order_id
     ORDER BY tp.created_at DESC
     LIMIT 5)
    
    ORDER BY created_at DESC
    LIMIT 10
";

$recentOrdersResult = $conn->query($recentOrdersQuery);
$recentOrders = [];

if ($recentOrdersResult) {
    while ($row = $recentOrdersResult->fetch_assoc()) {
        $recentOrders[] = $row;
    }
}

// Get latest customers (both delivery and takeaway)
$latestCustomersQuery = "
    (SELECT 
        hd.name as customer_name,
        hd.email,
        hd.phone,
        'Delivery' as order_type,
        dp.created_at
     FROM home_delivery hd
     JOIN delivery_payments dp ON hd.delivery_id = dp.delivery_id
     WHERE dp.payment_status = 'Success'
     ORDER BY dp.created_at DESC
     LIMIT 5)
    
    UNION ALL
    
    (SELECT 
        o.customer_name,
        '' as email,
        o.phone,
        'Takeaway' as order_type,
        tp.created_at
     FROM orders o
     JOIN takeaway_payments tp ON o.order_id = tp.order_id
     WHERE tp.payment_status = 'Success'
     ORDER BY tp.created_at DESC
     LIMIT 5)
    
    ORDER BY created_at DESC
    LIMIT 10
";

$latestCustomersResult = $conn->query($latestCustomersQuery);
$latestCustomers = [];

if ($latestCustomersResult) {
    while ($row = $latestCustomersResult->fetch_assoc()) {
        $latestCustomers[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
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
      height: 100vh;
      background-color: #f4f4f4;
    }

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
    }

    .stats-container {
      display: flex;
      justify-content: space-around;
      margin: 30px 0;
      gap: 20px;
    }

    .stat-box {
      background: white;
      padding: 30px;
      text-align: center;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      transition: 0.3s;
      flex: 1;
    }

    .stat-box:hover {
      transform: translateY(-5px);
    }

    .stat-box h3 {
      font-size: 28px;
      color: #2c3e50;
    }

    .stat-box p {
      font-size: 18px;
      color: #555;
    }

    .order-stats {
      display: flex;
      justify-content: space-around;
      margin: 20px 0;
      gap: 20px;
    }

    .order-stat-box {
      background: white;
      padding: 20px;
      text-align: center;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      flex: 1;
    }

    .order-stat-box h4 {
      font-size: 22px;
      color: #2c3e50;
    }

    .order-stat-box p {
      font-size: 16px;
      color: #555;
    }

    .delivery {
      border-top: 4px solid #3498db;
    }

    .takeaway {
      border-top: 4px solid #e74c3c;
    }

    .table-container {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      margin-top: 20px;
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

    .order-type-delivery {
      color: #3498db;
      font-weight: bold;
    }

    .order-type-takeaway {
      color: #e74c3c;
      font-weight: bold;
    }

    .status-success {
      color: #27ae60;
      font-weight: bold;
    }

    .status-pending {
      color: #f39c12;
      font-weight: bold;
    }

    .status-declined {
      color: #e74c3c;
      font-weight: bold;
    }

    @media (max-width: 768px) {
      .stats-container, .order-stats {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h2>Admin Panel</h2>
  <a href="admindash.php" class="active">Home</a>
  <a href="adminmenu.php">Menu & Items</a>
  <a href="adminpayment.php">Payments</a>
  <a href="adminlogin.html" onclick="logout()">Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
  <div class="header">Admin Dashboard</div>

  <div class="stats-container">
    <div class="stat-box">
      <h3><?php echo $totalOrders; ?></h3>
      <p>Total Orders</p>
    </div>
    
    <div class="stat-box">
      <h3><?php echo $menuCount; ?></h3>
      <p>Menu Items</p>
    </div>

    <div class="stat-box">
      <h3>₹<?php echo number_format($totalPayments, 2); ?></h3>
      <p>Total Payments</p>
    </div>
  </div>

  <div class="order-stats">
    <div class="order-stat-box delivery">
      <h4><?php echo $deliveryCount; ?></h4>
      <p>Delivery Orders</p>
      <p>₹<?php echo number_format($deliveryAmount, 2); ?></p>
    </div>
    
    <div class="order-stat-box takeaway">
      <h4><?php echo $takeawayCount; ?></h4>
      <p>Takeaway Orders</p>
      <p>₹<?php echo number_format($takeawayAmount, 2); ?></p>
    </div>
  </div>

  <!-- Customer Table -->
  <div class="table-container">
    <h2>Latest Customers</h2>
    <table>
      <thead>
        <tr>
          <th>Customer Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Order Type</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($latestCustomers)): ?>
          <tr>
            <td colspan="4" style="text-align: center;">No customers yet!</td>
          </tr>
        <?php else: ?>
          <?php foreach ($latestCustomers as $customer): ?>
            <tr>
              <td><?php echo htmlspecialchars($customer['customer_name']); ?></td>
              <td><?php echo htmlspecialchars($customer['email'] ?: 'N/A'); ?></td>
              <td><?php echo htmlspecialchars($customer['phone']); ?></td>
              <td class="order-type-<?php echo strtolower($customer['order_type']); ?>">
                <?php echo htmlspecialchars($customer['order_type']); ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Recent Orders -->
  <div class="table-container">
    <h2>Recent Orders</h2>
    <table>
      <thead>
        <tr>
          <th>Order Type</th>
          <th>Payment ID</th>
          <th>Customer</th>
          <th>Amount</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($recentOrders)): ?>
          <tr>
            <td colspan="5" style="text-align: center;">No recent orders found</td>
          </tr>
        <?php else: ?>
          <?php foreach ($recentOrders as $order): ?>
            <tr>
              <td class="order-type-<?php echo strtolower($order['order_type']); ?>">
                <?php echo htmlspecialchars($order['order_type']); ?>
              </td>
              <td>#<?php echo htmlspecialchars($order['payment_id']); ?></td>
              <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
              <td>₹<?php echo number_format($order['amount'], 2); ?></td>
              <td class="status-<?php echo strtolower($order['payment_status']); ?>">
                <?php echo htmlspecialchars($order['payment_status']); ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
  // Logout function
  function logout() {
    // Add logout functionality here
    window.location.href = 'adminlogin.html';
  }
</script>

</body>
</html>
