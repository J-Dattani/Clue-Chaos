<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cluechaos";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Generate a unique token for password reset
        $token = bin2hex(random_bytes(50));
        
        $expiry_time = date('Y-m-d H:i:s', strtotime('+1 hour'));  // Token expires in 1 hour

        // Update the token and expiry time in the database
        $sql = "UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $token, $expiry_time, $email);
        $stmt->execute();

        // Send reset email using PHPMailer
        require './vendor/autoload.php'; // Include the PHPMailer autoload

    
        
        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // SMTP server (e.g., Gmail)
            $mail->SMTPAuth = true;
            $mail->Username = 'cluechaos1503@gmail.com';  // Your SMTP username
            $mail->Password = 'vcia zpys zvvg eofp';  // Your SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('cluechaos1503@gmail.com', 'Clue Chaos');
            $mail->addAddress($email);  // Add the recipient email

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $resetLink = "http://localhost/clue_chaos/reset_password.php?token=$token";
            $mail->Body    = "Click the following link to reset your password: <a href=\"$resetLink\">Reset Password</a>";

            // Send email
            $mail->send();
            $success = "A password reset link has been sent to your email.";
        } catch (Exception $e) {
            $error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $error = "No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clue Chaos - Forgot Password</title>
    <div id="loader-placeholder"></div>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('./images/gradient.png');
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-form {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
  <!-- Include the loader HTML -->
  <script>
    // Fetch and include loader.html content
    fetch('loader.html')
      .then(response => response.text())
      .then(data => document.getElementById('loader-placeholder').innerHTML = data);
  </script>

<script>
    // loader.js
window.onload = function() {
  // Show the loader for 3 seconds
  setTimeout(function() {
    // Hide the loader after 3 seconds
    document.querySelector('.loader-container').style.display = 'none';
    
    // Show the main page content or redirect to another page
    document.querySelector('.content').style.display = 'block';
    
    // Or if you want to redirect to another page:
    // window.location.href = "your-main-page.html";
  }, 2000); // 3000ms = 3 seconds
};
</script>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 login-form">
                <h2 class="text-center mb-4">Forgot Password</h2>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                </form>
                <p class="text-center mt-3">
                    <a href="login.php">Back to Login</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
