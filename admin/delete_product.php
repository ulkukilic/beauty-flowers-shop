<?php
session_start();
include_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    header('Location: category.php');
    exit;
}


$stmt = $conn->prepare("SELECT name, image_file FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($productName, $productImage);
if (!$stmt->fetch()) {
    $stmt->close();
    header('Location: category.php');
    exit;
}
$stmt->close();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    // Delete from database
    $del = $conn->prepare("DELETE FROM products WHERE id = ?");
    $del->bind_param("i", $id);
    $del->execute();
    $del->close();
    // Delete image file
    @unlink(__DIR__ . '/../images/' . $productImage);
    header('Location: category.php?deleted=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Confirm Delete</title>
  <link rel="stylesheet" href="../css/category.css">
  <style>
        .footer {
     position: fixed;
    background: #f1f1f1;
    padding: 20px 0;
    font-size: 14px;
    text-align: center;
    bottom: 0;
    left: 0;
    width: 100%;
}

.social {
  position: fixed;
  bottom: 60px;  
  left: 0;
  width: 100%;
  background: #fff;
  padding: 10px 0;
  box-shadow: 0 -2px 5px rgba(0,0,0,0.1);
}

  </style>
 
</head>
<body>
<?php include_once '../layouts/menu.php'; ?>

<main class="container">
  <h1 class="admin-header">Delete Product</h1>
  <p style="text-align:center;">You are about to delete: <strong><?= htmlspecialchars($productName) ?></strong></p>
  <div style="text-align:center; margin-bottom:20px;">
    <img src="../images/<?= htmlspecialchars($productImage) ?>" alt="" style="max-width:200px; height:auto; border:1px solid #ccc; border-radius:8px;">
  </div>
  <div style="text-align:center;">
    <button id="openModal" class="btn-delete">Delete Product</button>
    <a href="category.php" class="btn-cancel">Cancel</a>
  </div>

  <!-- Modal -->
  <div id="deleteModal" class="modal-backdrop">
    <div class="modal">
      <h2>Delete "<?= htmlspecialchars($productName) ?>"?</h2>
      <p>Are you sure you want to delete this product permanently?</p>
      <div class="modal-buttons">
        <form method="post" style="display:inline;">
          <button type="submit" name="confirm_delete" class="btn-delete">Delete</button>
        </form>
        <button id="cancelBtn" class="btn-cancel">Cancel</button>
      </div>
    </div>
  </div>
</main>

<?php include_once '../layouts/footer.php'; ?>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const open = document.getElementById('openModal');
    const modal = document.getElementById('deleteModal');
    const cancel = document.getElementById('cancelBtn');
    open.addEventListener('click', () => { modal.style.display = 'flex'; });
    cancel.addEventListener('click', () => { modal.style.display = 'none'; });
  });
</script>
</body>
</html>
