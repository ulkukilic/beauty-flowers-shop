<?php

session_start();
include 'config.php';


if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {  // kullanicinin form girisleirni alir 
    $username         = trim($_POST['username'] ?? '');   // trim bosluklari kaldiri
    $email            = trim($_POST['email'] ?? '');
    $password         = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

     if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {  /// Ensure no field is empty
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
       
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");  
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $stmt->store_result();

         if ($stmt->num_rows > 0) {  // kullanici ciktiys
            $error = "Username or email already taken.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);  // Securely hash the password
            $insert = $conn->prepare(
                "INSERT INTO users (username, email, password) VALUES (?, ?, ?)"
            );
            $insert->bind_param("sss", $username, $email, $hashed);  

            if ($insert->execute()) {
                header('Location: login.php');
                exit;

            } else {
                
                $error = "Registration failed. Please try again.";
            }
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
  <title>Beauty Flowers - Register</title>
   <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

    <section >
        <div class="navbar-logo">
          <img src="../images/logo.jpeg" alt="Flowers Logo" class="logo-circle"> 
          Beauty Flowers
        </div>
    </section>
    
    <section>
       <main class="auth-form">
          <h2>Register</h2>

          <?php if ($error): ?>
              <p class="error"><?= $error ?></p> 
          <?php endif; ?>

          
          <form action="" method="POST">
              <label for="username">Username</label>
              <input type="text" name="username" id="username" required>

              <label for="email">Email</label>
              <input type="email" name="email" id="email" required>

              <label for="password">Password</label>
              <input type="password" name="password" id="password" required>

              <label for="confirm_password">Confirm Password</label>
              <input type="password" name="confirm_password" id="confirm_password" required>

              
              <button type="submit" class="btn red-btn">Sign Up</button>
              <p>Already have an account? <a href="login.php">Login</a></p>
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


