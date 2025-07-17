<?php
// admin/orders.php
session_start();
include_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$sql = "SELECT o.id, p.name AS product_name, o.first_name, o.last_name, o.phone, o.address, o.created_at
        FROM orders o
        JOIN products p ON o.product_id = p.id
        ORDER BY o.created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin – Customer Orders</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/css.css">
  <style>
    .orders-table {
      width: 90%; max-width: 1200px;
      margin: 40px auto;
      border-collapse: collapse;
      font-family: 'Arial', sans-serif;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .orders-table th, .orders-table td {
      border: 1px solid #ddd;
      padding: 12px;
      text-align: left;
    }
    .orders-table th {
      background-color: #f4f4f4;
      color: #7a2434;
      font-weight: 600;
    }
    .orders-table tr:nth-child(even) {
      background-color: #fafafa;
    }
    .orders-table tr:hover {
      background-color: #f1f1f1;
    }
    .orders-header {
      font-family: 'Playfair Display', serif;
      text-align: center;
      color: #a3001f;
      margin-top: 30px;
      font-size: 32px;
      font-style: italic;
    }
  </style>
</head>
<body>
<?php include_once '../layouts/menu.php'; ?>
<main>
  <h1 class="orders-header">Customer Orders</h1>
  <table class="orders-table">
    <thead>
      <tr>
        <th>Order ID</th>
        <th>Product</th>
        <th>Customer</th>
        <th>Phone</th>
        <th>Address</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result && $result->num_rows): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= nl2br(htmlspecialchars($row['address'])) ?></td>
            <td><?= date('Y-m-d H:i', strtotime($row['created_at'])) ?></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="6" style="text-align:center;">No orders found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</main>
<?php include_once '../layouts/footer.php'; ?>
</body>
</html>
