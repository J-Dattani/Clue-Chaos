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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
// Check if the query returned any rows
if ($result->num_rows > 0) {
    // Fetch the user data from the result
    $user = $result->fetch_assoc();
    
    // Check if the password matches
    if (password_verify($password, $user['password'])) {
        // Assign the user ID to the session
        $_SESSION['user_id'] = $user['id'];
        
        // Redirect to the start page
        header("Location: start.php");
        exit();
    } else {
        // Password doesn't match, show an error
        $error = "Invalid email or password";
    }
} else {
    // No user found, show an error
    $error = "Invalid email or password";
}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clue Chaos - Login</title>
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
                <h2 class="text-center mb-4">Login</h2>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                <p class="text-center mt-3">
                Forgot Password ?<a  style="text-decoration: wavy;" href="./forgot_password.php"> Forgot Password</a><br><br>
                    Don't have an account? <a  style="text-decoration: wavy;" href="register.php"> Register here</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>