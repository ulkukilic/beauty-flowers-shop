<?php

session_start();
include_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get product ID
$id = intval($_GET['id'] ?? 0);
if (!$id) {
    header('Location: category.php');
    exit;
}


$stmt = $conn->prepare("SELECT name, description, price, image_file FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($name, $description, $price, $currentImage);
if (!$stmt->fetch()) {
    $stmt->close();
    header('Location: category.php');
    exit;
}
$stmt->close();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price       = trim($_POST['price'] ?? '');

 
    if (empty($name) || empty($price)) {
        $error = 'Name and price are required.';
    } elseif (!is_numeric($price)) {
        $error = 'Price must be a number.';
    } else {
    
        $filename = $currentImage;
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext       = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $newName   = uniqid('prod_').".{$ext}";
            $targetDir = __DIR__ . '/../images/';
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $newName)) {
              
                @unlink($targetDir . $currentImage);
                $filename = $newName;
            } else {
                $error = 'Failed to upload new image.';
            }
        }

     
        if (!$error) {
            $upd = $conn->prepare(
                "UPDATE products SET name = ?, description = ?, price = ?, image_file = ? WHERE id = ?"
            );
            $upd->bind_param("ssdsi", $name, $description, $price, $filename, $id);
            if ($upd->execute()) {
                header('Location: category.php');
                exit;
            } else {
                $error = 'Database error: could not update.';
            }
            $upd->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin – Edit Product</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/category.css">
</head>
<body>
<?php include_once '../layouts/menu.php'; ?>

<main>
  <div class="form-container">
    <h2>Edit Product</h2>
    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form action="" method="POST" enctype="multipart/form-data">
      <label for="name">Product Name</label>
      <input type="text" name="name" id="name" value="<?= htmlspecialchars($name) ?>" required>

      <label for="description">Description</label>
      <textarea name="description" id="description"><?= htmlspecialchars($description) ?></textarea>

      <label for="price">Price (e.g. 29.99)</label>
      <input type="text" name="price" id="price" value="<?= htmlspecialchars($price) ?>" required>

      <label>Current Image</label>
      <img src="../images/<?= htmlspecialchars($currentImage) ?>" alt="" style="max-width:100%;height:auto;margin-bottom:10px;">

      <label for="image">Replace Image (optional)</label>
      <input type="file" name="image" id="image" accept="image/*">

      <button type="submit" class="btn-submit">Update Product</button>
    </form>
  </div>
</main>

<?php include_once '../layouts/footer.php'; ?>
</body>
</html>
