<?php
include 'config.php';
session_start();    
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
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
