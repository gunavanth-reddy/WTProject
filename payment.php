
<?php
session_start();
$orderItems = isset($_SESSION['orderItems']) ? $_SESSION['orderItems'] : [];
$customerName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
$orderType = isset($_SESSION['order_type']) ? $_SESSION['order_type'] : '';
$orderId = isset($_SESSION['last_order_id']) ? $_SESSION['last_order_id'] : '';

// Determine which script to use based on order type
$submitScript = ($orderType === 'delivery') ? 'submit_upi_request_delivery.php' : 'submit_upi_request_takeaway.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Payment - Savory Seasons</title>
  <style>
    /* Your existing CSS */
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f9f9f9;
      margin: 0;
      padding: 0;
    }

    .header {
      background-color: #ffffff;
      padding: 20px;
      text-align: center;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .header h2 {
      margin: 0;
      font-size: 26px;
      color: #333;
    }

    .header span {
      color: #e67e22;
    }

    .container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      padding: 30px;
    }

    .form-section, .summary-section {
      background-color: #fff;
      border-radius: 10px;
      padding: 25px;
      margin: 10px;
      flex: 1 1 300px;
      max-width: 450px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    h3 {
      margin-top: 0;
      color: #555;
    }

    input[type="text"],
    input[type="submit"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0 20px;
      border: 1px solid #ccc;
      border-radius: 6px;
      box-sizing: border-box;
      font-size: 15px;
    }

    input[type="submit"] {
      background-color: #e67e22;
      color: white;
      border: none;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    input[type="submit"]:hover {
      background-color: #cf5d0e;
    }

    ul {
      list-style: none;
      padding: 0;
    }

    ul li {
      margin: 10px 0;
      font-size: 16px;
      display: flex;
      justify-content: space-between;
    }

    .total {
      font-weight: bold;
      font-size: 18px;
      margin-top: 15px;
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
        align-items: center;
      }
    }
  </style>
</head>
<body>

  <div class="header">
    <h2>Savory <span>Seasons</span> — UPI Payment</h2>
  </div>

  <div class="container">
    <div class="form-section">
      <h3>UPI Payment Request</h3>
      <form action="<?php echo $submitScript; ?>" method="post">
        <label>Customer Name</label>
        <input type="text" name="customer_name" value="<?php echo htmlspecialchars($customerName); ?>" readonly />

        <label>Payment Method</label>
        <input type="text" value="UPI" readonly />

        <label>Order Type</label>
        <input type="text" value="<?php echo ucfirst($orderType); ?>" readonly />

        <?php 
          $total = 0;
          foreach ($orderItems as $item) {
            $total += $item['price'] * $item['quantity'];
          }
        ?>
        <input type="hidden" name="amount" value="<?php echo $total; ?>" />
        <input type="hidden" name="order_id" value="<?php echo $orderId; ?>" />

        <input type="submit" value="Send UPI Request" />
      </form>
    </div>

    <div class="summary-section">
      <h3>Order Summary</h3>
      <?php if (!empty($orderItems)) { 
        echo "<ul>";
        foreach ($orderItems as $itemName => $item) {
          $qty = $item['quantity'];
          $price = $item['price'];
          $subtotal = $qty * $price;
          echo "<li>{$itemName} (x{$qty}) <span>₹{$subtotal}</span></li>";
        }
        echo "</ul>";
        echo "<div class='total'>Total: ₹{$total}</div>";
      } else {
        echo "<p>No items in your order.</p>";
      } ?>
    </div>
  </div>

</body>
</html>
