<?php
session_start();

// Yetki kontrolü (isterseniz POST kontrolünü buraya alın)
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'config.php';  // DB bağlantısını burada include edin

    // POST verilerini al
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName  = trim($_POST['lastName']  ?? '');
    $phone     = trim($_POST['phone']     ?? '');
    $message   = trim($_POST['message']   ?? '');

    // Basit validasyon
    if ($firstName === '' || $lastName === '' || $phone === '' || $message === '') {
        header('Location: ../contact.html?error=empty');
        exit;
    }

    // Veritabanına kaydet (örnek PDO ile)
    $stmt = $pdo->prepare(
      "INSERT INTO contacts (first_name, last_name, phone, message) VALUES (?, ?, ?, ?)"
    );
    if ($stmt->execute([$firstName, $lastName, $phone, $message])) {
        // Başarı → kullanıcıya geri yönlendir
        header('Location: ../contact.html?success=1');
        exit;
    } else {
        header('Location: ../contact.html?error=db');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beauty Flowers Shop</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
  
    <link rel="stylesheet" href="../css/css.css">
</head>
<body>
<?php include_once '../layouts/menu.php'; ?>




<?php include_once '../layouts/footer.php'; ?>

<script src="../js/javascript.js"></script>
</body>
</html>
