<?php
session_start();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($user_id) {
    include('./conn/db_connection.php');  // Include your database connection file

    // Prepare the query
    $query = "SELECT group_id, group_name FROM `groups` WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    
    // Error check for the statement preparation
    if ($stmt === false) {
        die('Error preparing the statement: ' . $conn->error);
    }

    $stmt->bind_param('i', $user_id);
    $stmt->execute();

    // Error check for execution
    if ($stmt->error) {
        die('Error executing the statement: ' . $stmt->error);
    }

    $result = $stmt->get_result();
    $groups = [];

    // Check if any rows were returned
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $groups[] = $row;  // Store each group in the array
        }
    } else {
        echo "No groups found for the user.";
    }

    $stmt->close();
    echo json_encode($groups);  // Return the groups as JSON
} else {
    echo json_encode([]);  // If no user is logged in, return an empty array
}

?>
