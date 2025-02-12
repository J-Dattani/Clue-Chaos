<?php
session_start();

// Check if user is logged in
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (!isset($_SESSION['user_id'])) {
    $is_logged_in = false;
} else {
    $is_logged_in = true;
}
// Database connection
$mysqli = new mysqli('localhost', 'root', '', 'cluechaos');

// Check the connection
if ($mysqli->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $mysqli->connect_error]));
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['players'])) {
    $players = $_POST['players'];

    if (empty($players)) {
        die(json_encode(['status' => 'error', 'message' => 'No players provided.']));
    }

    // Step 1: Create a new group
    $result = $mysqli->query("SELECT COUNT(*) AS group_count FROM `groups`");
    if (!$result) {
        die(json_encode(['status' => 'error', 'message' => 'Error fetching group count: ' . $mysqli->error]));
    }

    $row = $result->fetch_assoc();
    $group_number = $row['group_count'] + 1;
    $group_name = "Group " . $group_number;

    // Step 2: Insert the group into the 'groups' table
    $stmt = $mysqli->prepare("INSERT INTO `groups` (user_id, group_name) VALUES (?, ?)");
    if (!$stmt) {
        die(json_encode(['status' => 'error', 'message' => 'Error preparing statement for groups: ' . $mysqli->error]));
    }

    $stmt->bind_param("is", $user_id, $group_name);
    if (!$stmt->execute()) {
        die(json_encode(['status' => 'error', 'message' => 'Error creating group: ' . $stmt->error]));
    }

    $group_id = $mysqli->insert_id;

    // Step 3: Insert each player into the 'members' table
    $stmt = $mysqli->prepare("INSERT INTO `members` (group_id, member_name) VALUES (?, ?)");
    if (!$stmt) {
        die(json_encode(['status' => 'error', 'message' => 'Error preparing statement for members: ' . $mysqli->error]));
    }

    foreach ($players as $player) {
        $player = trim($player);
        if (!empty($player)) {
            $stmt->bind_param("is", $group_id, $player);
            if (!$stmt->execute()) {
                die(json_encode(['status' => 'error', 'message' => 'Failed to add player ' . $player . ': ' . $stmt->error]));
            }
        }
    }

    // Store the group ID and member IDs in the session
    $_SESSION['group_id'] = $group_id;

    // Send JSON response to indicate success
    echo json_encode(['status' => 'success', 'group_name' => $group_name]);
    exit;
}

?>

  
<!DOCTYPE html>  
<html lang="en">  
<head>  
   <meta charset="UTF-8">  
   <meta name="viewport" content="width=device-width, initial-scale=1.0">  
   <title>Clue Chaos - Player Setup</title>  
   <div id="loader-placeholder"></div>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">  
   <style>  
      .hidden { display: none; }  
      body {  
        margin: 0;  
        height: 100vh;  
        background-image: url('./images/gradient.png');  
        background-size: cover;  
        overflow: hidden;
        background-position: center;  
        backdrop-filter: blur(5px);  
        color: #1A1A2E;  
      }  
      .container {  
        display: flex;  
        flex-direction: column;  
        justify-content: center;  
        align-items: center;  
        height: 100%;  
        text-align: center;  
      }  
      h1 {  
        color: #1A1A2E;  
        margin-bottom: 20px;  
      }  
      .form-label, .btn {  
        color: #1A1A2E;  
      }  
      .back-button {  
        position: absolute;  
        top: 20px;  
        right: 20px;  
      }  
   </style>  
</head>  
<body>  


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

   <button class="btn btn-primary back-button" style="color: white;" onclick="window.location.href='start.php'">Go Back</button>  

   <div class="container mt-5">  
      <h1 class="text-center">Player Setup</h1>  
      <form id="playerSetupForm" method="POST">  
        <div id="playerInputs"></div>  
  
        <button type="submit" id="setupPlayers" class="btn btn-primary mt-3">Set Up Players</button>  
      </form>  
   </div>  


  
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  
   <?php if ($is_logged_in): ?>  
   <script>  
   $(document).ready(function() {  
      const playerCount = parseInt(localStorage.getItem('playerCount'), 10);  
       
      if (!playerCount || playerCount < 3) {  
        alert('Invalid number of players. Redirecting to the start page.');  
        window.location.href = 'start.html';  
        return;  
      }  
  
      const playerInputsContainer = $('#playerInputs');  
      for (let i = 1; i <= playerCount; i++) {  
        playerInputsContainer.append(`  
           <div class="mb-3">  
              <label for="player${i}" class="form-label">Player ${i} Name:</label>  
              <input type="text" class="form-control playerName" id="player${i}" name="players[]" placeholder="Enter Player ${i} Name" required>  
           </div>  
        `);  
      }  
  
      $('#playerSetupForm').submit(function(event) {  
        event.preventDefault();  
  
        let players = $('.playerName').map(function() {  
           return $(this).val().trim();  
        }).get().filter(name => name !== "");  
  
        if (players.length === 0) {  
           alert("No valid player names provided.");  
           return;  
        }  
  
        console.log("Players:", players);  
  
        $.ajax({  
           url: 'player_setup.php',  
           type: 'POST',  
           data: { players: players },  
           dataType: 'json',  
           success: function(data) {  
              console.log("Response from PHP:", data);  
  
              if (data.status === 'success') {  
                alert("Group '" + data.group_name + "' created successfully!");  
                localStorage.setItem('players', JSON.stringify(players));  
                localStorage.setItem('groupName', data.group_name);  
                window.location.href = 'word_reveal.php';  
              } else {  
                alert("Error: " + (data.message || "Unknown error occurred."));  
              }  
           },  
           error: function(xhr, status, error) {  
              console.log("AJAX Error:", error);  
              alert("An error occurred while creating the group.");  
           }  
        });  
      });  
   });  
   </script>  
   <?php else: ?>  
   <script src="./scripts/player_setup.js"></script>  
   <?php endif; ?>  
</body>  
</html>
