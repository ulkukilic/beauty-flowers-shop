<?php
include 'config.php';
session_start();    
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
} 
$sql = "SELECT * FROM products ORDER BY id DESC";
$result = $conn->query($sql);  

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beauty Flowers Shop</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
  
    <link rel="stylesheet" href="../css/category.css">
</head>
<body>
<?php include_once '../layouts/menu.php'; ?>

<main>
    <div class="admin-header"><h1> Manage Products</h1></div>
    <div class="admin-actions">
        <a href="add_product.php" class="btn-primary">Add New Product</a>
    </div>
     
    <div class="product-list">
        <?php if ($result && $result->num_rows): ?>
            <?php while ($prod = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <img src="../images/<?= htmlspecialchars($prod['image_file']) ?>" alt="<?= htmlspecialchars($prod['name']) ?>">
                    <div class="product-info">
                        <h3><?= htmlspecialchars($prod['name']) ?></h3>
                        <p>$<?= number_format($prod['price'], 2) ?></p>
                    </div>
                    <div class="admin-controls">
                        <a href="edit_product.php?id=<?= $prod['id'] ?>" class="btn-secondary">Edit</a>
                        <a href="delete_product.php?id=<?= $prod['id'] ?>" class="btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center; width:100%;">No products found.</p>
        <?php endif; ?>
    </div>
</main>



<?php include_once '../layouts/footer.php'; ?>

</body>
</html>
