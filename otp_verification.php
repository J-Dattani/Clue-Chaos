<?php
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

if (!isset($_SESSION['otp']) || !isset($_SESSION['email']) || !isset($_SESSION['name']) || !isset($_SESSION['password'])) {
    header("Location: register.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_otp = $_POST['otp'];
    if ($user_otp == $_SESSION['otp']) {
        $name = $_SESSION['name'];
        $email = $_SESSION['email'];
        $password = password_hash($_SESSION['password'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);
        
        if ($stmt->execute()) {
            $success = "Registration successful. You can now login.";
            unset($_SESSION['otp']);
            unset($_SESSION['name']);
            unset($_SESSION['email']);
            unset($_SESSION['password']);
        } else {
            $error = "Error: " . $stmt->error;
        }
    } else {
        $error = "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clue Chaos - OTP Verification</title>
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
        .otp-form {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 otp-form">
                <h2 class="text-center mb-4">OTP Verification</h2>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                    <p class="text-center">
                        <a href="login.php" class="btn btn-primary">Go to Login</a>
                    </p>
                <?php else: ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="otp" class="form-label">Enter OTP</label>
                            <input type="text" class="form-control" id="otp" name="otp" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Verify OTP</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>