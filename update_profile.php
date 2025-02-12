<?php
session_start();

if (isset($_POST['username']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $username = $_POST['username'];

    include('./conn/db_connection.php');  // Include your database connection file

    // Update user details in the database
    $query = "UPDATE users SET name = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $username, $user_id);
    if ($stmt->execute()) {
        echo 'Profile updated successfully';
    } else {
        echo 'Error updating profile';
    }
    $stmt->close();
} else {
    echo 'Invalid request';
}
?>
