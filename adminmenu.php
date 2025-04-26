<?php
session_start();
include 'db_connect.php';

// Process stock toggle if submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['item_id']) && isset($_POST['action'])) {
    $item_id = $_POST['item_id'];
    $action = $_POST['action'];
    
    // Determine new availability status
    $availability = ($action === 'restock') ? 1 : 0;
    
    // Update item availability
    $stmt = $conn->prepare("UPDATE menu SET availability = ? WHERE id = ?");
    $stmt->bind_param("ii", $availability, $item_id);
    
    if ($stmt->execute()) {
        $statusMessage = ($action === 'restock') ? "Item restocked successfully." : "Item marked as out of stock.";
        
        // Update session data with the new menu information
        include 'load_menu_data.php';
    } else {
        $statusMessage = "Error updating item status: " . $conn->error;
    }
}

// Fetch all menu items
$menuItems = [];
$query = "SELECT id, item_name, category, price, availability FROM menu ORDER BY category, item_name";
$result = $conn->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $menuItems[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menu & Items - Admin</title>
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

    /* Menu Tables */
    .table-container {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
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

    .stock-btn {
      padding: 6px 12px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.3s;
    }

    .btn-outofstock {
      background-color: #e74c3c;
      color: white;
    }

    .btn-restock {
      background-color: #27ae60;
      color: white;
    }

    .stock-btn:hover {
      opacity: 0.8;
    }

    @media (max-width: 768px) {
      .sidebar {
        width: 180px;
      }

      th, td {
        padding: 10px;
      }
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h2>Admin Panel</h2>
  <a href="admindash.php">Home</a>
  <a href="adminmenu.php" class="active">Menu & Items</a>
  <a href="adminpayment.php">Payments</a>
  <a href="adminlogin.html" onclick="logout()">Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">

  <!-- Header -->
  <div class="header">
    Menu & Items Management
  </div>

  <?php if (isset($statusMessage)): ?>
    <div class="status-message <?php echo strpos($statusMessage, 'Error') !== false ? 'error-message' : 'success-message'; ?>">
      <?php echo $statusMessage; ?>
    </div>
  <?php endif; ?>

  <!-- Out of Stock Items Table -->
  <div class="table-container">
    <h2>Out of Stock Items</h2>
    <table id="outofstock-table">
      <thead>
        <tr>
          <th>Item Name</th>
          <th>Category</th>
          <th>Price (₹)</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $outOfStockFound = false;
        foreach ($menuItems as $item): 
          if ($item['availability'] == 0):
            $outOfStockFound = true;
        ?>
          <tr>
            <td><?php echo htmlspecialchars($item['item_name']); ?></td>
            <td><?php echo htmlspecialchars($item['category']); ?></td>
            <td>₹<?php echo number_format($item['price'], 2); ?></td>
            <td>
              <form method="POST" action="" style="display: inline;">
                <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                <input type="hidden" name="action" value="restock">
                <button type="submit" class="stock-btn btn-restock">Restock</button>
              </form>
            </td>
          </tr>
        <?php 
          endif;
        endforeach; 
        
        if (!$outOfStockFound):
        ?>
          <tr>
            <td colspan="4" style="text-align: center;">No items are currently out of stock.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Available Items Table -->
  <div class="table-container">
    <h2>Available Items</h2>
    <table id="available-table">
      <thead>
        <tr>
          <th>Item Name</th>
          <th>Category</th>
          <th>Price (₹)</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $availableFound = false;
        foreach ($menuItems as $item): 
          if ($item['availability'] == 1):
            $availableFound = true;
        ?>
          <tr>
            <td><?php echo htmlspecialchars($item['item_name']); ?></td>
            <td><?php echo htmlspecialchars($item['category']); ?></td>
            <td>₹<?php echo number_format($item['price'], 2); ?></td>
            <td>
              <form method="POST" action="" style="display: inline;">
                <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                <input type="hidden" name="action" value="outofstock">
                <button type="submit" class="stock-btn btn-outofstock">Out of Stock</button>
              </form>
            </td>
          </tr>
        <?php 
          endif;
        endforeach; 
        
        if (!$availableFound):
        ?>
          <tr>
            <td colspan="4" style="text-align: center;">No available items found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</div>

<script>
  // Logout function
  function logout() {
    // You can add AJAX request to destroy session here
    window.location.href = 'adminlogin.html';
  }
</script>

</body>
</html>
