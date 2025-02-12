<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './vendor/autoload.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cluechaos";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Password validation regex
    $password_pattern = "/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";

    if ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } elseif (!preg_match($password_pattern, $password)) {
        $error = "Password must be at least 8 characters long, include an uppercase letter, a number, and a special character.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $error = "Email already exists";
        } else {
            // Generate OTP
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password;

            // Send OTP email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'cluechaos1503@gmail.com';
                $mail->Password = 'vcia zpys zvvg eofp';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('cluechaos1503@gmail.com', 'Clue Chaos');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Your OTP for Registration';
                $mail->Body    = "Your OTP is: $otp";

                $mail->send();
                header("Location: otp_verification.php");
                exit();
            } catch (Exception $e) {
                $error = "Error sending OTP: " . $mail->ErrorInfo;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clue Chaos - Register</title>
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
        .register-form {
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
  }, 1000); // 3000ms = 3 seconds
};

</script>


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 register-form">
                <h2 class="text-center mb-4">Register</h2>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
                <p class="text-center mt-3">
                    Already have an account? <a  style="text-decoration: wavy;" href="login.php">Login here</a>
                </p>
            </div>
        </div>
    </div>

    <script>
document.querySelector("form").addEventListener("submit", function(e) {
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirm_password").value;
    const passwordPattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    if (password !== confirmPassword) {
        e.preventDefault();
        alert("Passwords do not match.");
        return;
    }

    if (!passwordPattern.test(password)) {
        e.preventDefault();
        alert("Password must be at least 8 characters long, include an uppercase letter, a number, and a special character.");
        return;
    }
});
</script>


</body>
</html>