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

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token exists in the database and is not expired
    $sql = "SELECT * FROM users WHERE reset_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $error = "Invalid or expired token.";
    } else {
        $user = $result->fetch_assoc();
        $expiry_time = strtotime($user['reset_token_expiry']);
        
        // Check if the token has expired (e.g., 1 hour expiry)
        if ($expiry_time < time()) {
            $error = "The reset token has expired.";
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($error)) {
            $new_password = $_POST['password'];
        
            // Password validation regex
            $password_pattern = "/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
        
            if (!preg_match($password_pattern, $new_password)) {
                $error = "Password must be at least 8 characters long, include an uppercase letter, a number, and a special character.";
            } else {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
                // Update the user's password and remove the reset token
                $sql = "UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $hashed_password, $token);
                if ($stmt->execute()) {
                    $success = "Your password has been successfully reset.";
                } else {
                    $error = "Failed to reset password.";
                }
            }
        }
        
    }
} else {
    $error = "No token provided.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clue Chaos - Reset Password</title>
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
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 login-form">
                <h2 class="text-center mb-4">Reset Password</h2>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                </form>
                <p class="text-center mt-3">
                    <a href="login.php">Back to Login</a>
                </p>
            </div>
        </div>
    </div>

    <script>
document.querySelector("form").addEventListener("submit", function(e) {
    const password = document.getElementById("password").value;
    const passwordPattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    if (!passwordPattern.test(password)) {
        e.preventDefault();
        alert("Password must be at least 8 characters long, include an uppercase letter, a number, and a special character.");
        return;
    }
});
</script>


</body>
</html>
