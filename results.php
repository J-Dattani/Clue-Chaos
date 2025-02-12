<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database configuration
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'cluechaos';

// Create a connection to the database
try {
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

session_start();

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']) ? true : false;
$user_id = $is_logged_in ? $_SESSION['user_id'] : null;

if ($is_logged_in) {
    // die("Error: User not logged in.");


// Check if group_id is already stored in the session
if (!isset($_SESSION['group_id'])) {
    try {
        // Fetch the group_id for the logged-in user
        $sql = "SELECT group_id FROM members WHERE user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && isset($result['group_id'])) {
            $_SESSION['group_id'] = $result['group_id']; // Store group_id in the session
        } else {
            die("Error: Group ID not found for the user.");
        }
    } catch (PDOException $e) {
        die("Error fetching group ID: " . $e->getMessage());
    }
}

// Get group_id from session
$group_id = $_SESSION['group_id'];

// Handle the players' data submission
if (isset($_POST['players'])) {
    // Decode the players data from the POST request
    $players = json_decode($_POST['players'], true);

    if (!is_array($players)) {
        die("Invalid players data.");
    }

    try {
        // Start transaction
        $conn->beginTransaction();

        // SQL query to insert or update player results
        $insertUpdateSql = "INSERT INTO game_results (member_id, group_id, name, score) 
                            VALUES (:member_id, :group_id, :name, :score)
                            ON DUPLICATE KEY UPDATE score = :score";
        $insertUpdateStmt = $conn->prepare($insertUpdateSql);

        // Iterate over the players and either update or insert each result
        foreach ($players as $player) {
            $score = 0;

            // Calculate the score based on the player's role
            if ($player['role'] === 'Normal') {
                $score = 2;
            } elseif ($player['role'] === 'Undercover') {
                $score = 3;
            } elseif ($player['role'] === 'Mr White') {
                $score = 5;
            }

            // Insert or update the player's score
            $insertUpdateStmt->bindValue(':member_id', $user_id, PDO::PARAM_INT);
            $insertUpdateStmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
            $insertUpdateStmt->bindValue(':name', $player['name'], PDO::PARAM_STR);
            $insertUpdateStmt->bindValue(':score', $score, PDO::PARAM_INT);
            $insertUpdateStmt->execute();
        }

        // Commit transaction
        $conn->commit();
        echo "<script>alert('Game results saved successfully.');</script>";
    } catch (PDOException $e) {
        // Rollback transaction in case of error
        $conn->rollBack();
        echo "Error saving game results: " . $e->getMessage();
    }
} else {
    // echo "No players data received.";
}
}
?>
  



<!DOCTYPE html>  
<html lang="en">  
<head>  
   <meta charset="UTF-8">  
   <meta name="viewport" content="width=device-width, initial-scale=1.0">  
   <title>Clue Chaos - Results</title>  
   <div id="loader-placeholder"></div>  
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">  
   <?php if ($is_logged_in): ?>  
   <style>  
      body { background-color: #f8f9fa; }  
      .result-page { text-align: center; margin-top: 50px; }  
      .scoreboard {  
        margin-top: 30px;  
        background-color: white;  
        border-radius: 10px;  
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);  
        padding: 20px;  
      }  
      .scoreboard table { width: 100%; border-collapse: collapse; }  
      .scoreboard th, .scoreboard td {  
        padding: 10px;  
        text-align: center;  
        border-bottom: 1px solid #ddd;  
      }  
      .scoreboard th { background-color: #007bff; color: white; }  
      .btn-play-again { margin-top: 20px; }  
   </style>  
   <?php endif; ?>  
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
  
  // Redirect to the main page  
  window.location.href = "#";  
  }, 2000); // 3000ms = 3 seconds  
};  
</script>  
  
<div class="container result-page mb-4">  
   <h1 class="mb-4 text-center" id="gameResult"></h1>  
   <?php if ($is_logged_in): ?>  
   <div class="scoreboard">  
      <h3>Scoreboard</h3>  
      <table>  
        <thead>  
           <tr>  
              <th>Name</th>  
              <th>Score</th>  
           </tr>  
        </thead>  
        <tbody id="scoreTable"></tbody>  
      </table>  
   </div>  
   <?php endif; ?>  
   <?php if ($is_logged_in): ?>  
   <form id="save-data-form" method="post">  
      <input type="hidden" id="players" name="players">  
      <button type="submit" class="btn btn-secondary btn-play-again">Save Data</button>  
   </form>  
   <?php endif; ?>
  
</div>  
  
<div class="d-flex justify-content-center align-items-center" style="margin-top: 20px;">  
   <button id="playAgain" class="btn btn-secondary btn-play-again">Play Again</button>  
</div>  
  
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  
<?php if ($is_logged_in): ?>  
<script>  
document.addEventListener('DOMContentLoaded', function() {  
  // Fetch data from localStorage  
  const gameResult = localStorage.getItem('gameResult');  
  const players = JSON.parse(localStorage.getItem('players') || '[]');  
  
  // Display game result  
  document.getElementById('gameResult').textContent = gameResult;  
  
  // Populate the scoreboard  
  const scoreTable = document.getElementById('scoreTable');  
  players.forEach(player => {  
    const score = player.role === 'Normal' ? 2 :  
          player.role === 'Undercover' ? 3 :  
          player.role === 'Mr White' ? 5 : 0;  
  
    const row = scoreTable.insertRow();  
    const nameCell = row.insertCell(0);  
    const scoreCell = row.insertCell(1);  
    nameCell.textContent = player.name;  
    scoreCell.textContent = score;  
  });  
  
  // Populate the form field with player data  
  const playersInput = document.getElementById('players');  
  playersInput.value = JSON.stringify(players.map(player => {  
    return {  
      name: player.name,  
      role: player.role,  
      score: player.role === 'Normal' ? 2 :  
        player.role === 'Undercover' ? 3 :  
        player.role === 'Mr White' ? 5 : 0  
    };  
  }));  
  
  // Handle Play Again button click  
  document.getElementById('playAgain').addEventListener('click', function() {  
    localStorage.removeItem('players');  
    localStorage.removeItem('gameResult');  
    localStorage.removeItem('scores');  
    window.location.href = 'start.php';  
  });  
});  
</script>  
<?php else: ?>  
<!-- If user is NOT logged in, include the external JavaScript file for guest mode -->  
<script src="./scripts/results.js"></script>  
<?php endif; ?>  
</body>  
</html>
