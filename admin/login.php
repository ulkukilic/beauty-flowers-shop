<?php
session_start();   
include 'config.php';

if(isset($_SESSION['user_id'])) {
    // User is logged in, redirect to index page
    header('Location: index.php');
    exit;
} 

$error="";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
  
  
     if (empty($username) || empty($password)) {  // sifre bos olmamali ve en az 8 karakter olmali
        $error = "Username and password are required.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } else {
  

        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");  // Prepare the SQL statement
        $stmt->bind_param("s", $username);  // kullanici adini bagla
        $stmt->execute();  // 
        $stmt->store_result(); // dogrulama icin sonucu sakla

         if ($stmt->num_rows === 1) {    // Kullanici adi varsa
            $stmt->bind_result($id, $hashed_password);  //sifreyi ve id'yi bagla
            $stmt->fetch();
            
            if (password_verify($password, $hashed_password)) {  // Sifre dogruysa sql bilgileri ile user_id'yi session'a kaydet
                $_SESSION['user_id'] = $id;
                header('Location: index.php');
                exit;
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Beauty Flowers - Login  </title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/styles.css">

</head>
<body>

  <section>
    <div class='navbar-logo'>
      <img src="../images/logo.jpeg" alt="Beauty Flowers Logo" class="logo-circle"> 
          Beauty Flowers
    </div>
  </section>

  <section class='login-container'>
      <main class="auth-form">
        <h2>Login</h2>
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
          <form action="" method="POST">
              <label for="username">Username</label>
              <input type="text" name="username" id="username" required>

              <label for="password">Password</label>
              <input type="password" name="password" id="password" required>

              <button type="submit" class="btn login-btn">Login</button>
              <p>Don't have an account? <a href="register.php">Sign up</a></p>
          </form>
    </main>
  </section>


  <section class="social">
    <div class="container text-center">
        <ul>
            <li><a href="#"><img src="https://img.icons8.com/fluent/50/000000/facebook-new.png"/></a></li>
            <li><a href="https://www.instagram.com/ellifulku/"><img src="https://img.icons8.com/fluent/48/000000/instagram-new.png"/></a></li>
            <li><a href="#"><img src="https://img.icons8.com/fluent/48/000000/twitter.png"/></a></li>
        </ul>
    </div>
</section>

    <section class="footer">
      <div class="container text-center">
          <p>© 2025 Beauty Flowers. All rights reserved.</p>
      </div>
    </section>

 <script src="js/javascript.js"></script>  
</body>
</html>