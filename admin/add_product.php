<?php

session_start();
include_once 'config.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price       = trim($_POST['price'] ?? '');
    
   
    if (empty($name) || empty($price)) {
        $error = 'Name and price are required.';
    } elseif (!is_numeric($price)) {
        $error = 'Price must be a number.';
    } elseif (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Please select an image.';
    } else {
         
        $ext       = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION); //
        $filename  = uniqid('prod_') . "." . $ext;
        $targetDir = __DIR__ . '/../images/';
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $filename)) {
            $error = 'Failed to upload image.';
        } else {
            
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, image_file) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssds", $name, $description, $price, $filename);
            if ($stmt->execute()) {
                header('Location: category.php');
                exit;
            } else {
                $error = 'Database error: could not save product.';
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin – Add Product</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/category.css">
  
</head>
<body>
<?php include_once '../layouts/menu.php'; ?>

<main>
    <div class="form-container">
        <h2>Add New Product</h2>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="name">Product Name</label>
            <input type="text" name="name" id="name" required>

            <label for="description">Description</label>
            <textarea name="description" id="description"></textarea>

            <label for="price">Price (e.g. 29.99)</label>
            <input type="text" name="price" id="price" required>

            <label for="image">Product Image</label>
            <input type="file" name="image" id="image" accept="image/*" required>

            <button type="submit" class="btn-submit">Save Product</button>
        </form>
    </div>
</main>

<?php include_once '../layouts/footer.php'; ?>
</body>
</html>
