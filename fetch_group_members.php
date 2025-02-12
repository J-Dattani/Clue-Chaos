<?php
session_start();
include('./conn/db_connection.php');

// Get the group_id from the POST request
$group_id = $_POST['group_id'];

// Validate the group_id input
if (!is_numeric($group_id)) {
    die(json_encode(['error' => 'Invalid group_id']));
}

try {
    // SQL query to fetch game results for the specific group_id
    $query = "
        SELECT 
            gr.name AS member_name, 
            COALESCE(gr.score, 0) AS score 
        FROM 
            game_results AS gr
        WHERE 
            gr.group_id = ?  -- Filter by group_id only
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $group_id); // Bind group_id as an integer
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row; // Collect results in an array
    }

    $stmt->close();

    // Return the results as JSON
    echo json_encode($data);
} catch (Exception $e) {
    // Handle exceptions and return an error response
    echo json_encode(['error' => 'Error fetching data: ' . $e->getMessage()]);
}
?>
