<?php  
session_start();  
// Check if user is logged in  
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;  


  
// Check if user is logged in  
if (!isset($_SESSION['user_id'])) {  
   $is_logged_in = false; // User is not logged in  
} else {  
   $is_logged_in = true;  // User is logged in  
   $user_id = $_SESSION['user_id'];  // Get user ID from session  
}  
?>  
  
<!DOCTYPE html>  
<html lang="en">  
<head>  
   <meta charset="UTF-8">  
   <meta name="viewport" content="width=device-width, initial-scale=1.0">  
   <title>Clue Chaos - Word Reveal</title>  
   <div id="loader-placeholder"></div>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">  
   <style>  
      .hidden { display: none; }  
      .card {  
        cursor: pointer;  
        transition: transform 0.2s; /* Smooth hover effect */  
        margin: 10px; /* Spacing between cards */  
        width: 100%; /* Make cards full width of their column */  
        max-width: 200px; /* Set a max-width for each card */  
      }  
      .card:hover {  
        transform: scale(1.05); /* Slight zoom effect on hover */  
      }  
      .disabled-card {  
        pointer-events: none;  
        opacity: 0.6;  
      }  
  
      /* Background and text styles */  
      body {  
        margin: 0; /* Remove default margin */  
        height: 100vh; /* Full height */  
        background-image: url('./images/gradient.png'); /* Background image */  
        background-size: cover; /* Cover the entire background */  
        background-position: center; /* Center the background image */  
        backdrop-filter: blur(5px); /* Blur effect for readability */  
        color: #1A1A2E; /* Set text color to #1A1A2E */  
        position: relative; /* Set position to relative to position child elements absolutely */  
      }  
  
      .back-button {  
        position: absolute; /* Position button absolutely */  
        top: 20px; /* Distance from the top */  
        right: 20px; /* Distance from the right */  
      }  
  
      .container {  
        display: flex;  
        flex-direction: column; /* Align children in a column */  
        justify-content: flex-start; /* Align to the top */  
        align-items: center; /* Center horizontally */  
        height: 100%; /* Full height */  
        text-align: center; /* Center-align text */  
        padding-top: 60px; /* Add some padding from the top to prevent overlap with the button */  
      }  
  
      h2 {  
        color: #1A1A2E; /* Header color */  
        margin-bottom: 20px; /* Add space below the header */  
      }  
  
      .form-label, .btn {  
        color: #1A1A2E; /* Set label and button color */  
      }  
  
      /* Popup styles */  
      .modal-content {  
        background-color: #007bff; /* Blue background for the modal */  
        color: white; /* White text color for all modal content */  
        border: 1px solid #0056b3; /* Darker blue border */  
      }  
      .modal-header {  
        background-color: #0056b3; /* Darker blue for the header */  
        color: white; /* White text */  
      }  
      .modal-footer .btn-primary {  
        background-color: #007bff; /* Primary button color */  
        border: none; /* Remove border */  
      }  
      .modal-footer .btn-secondary {  
        background-color: white; /* Change secondary button background to white */  
        color: #007bff; /* Change secondary button text color to blue */  
        border: 1px solid #007bff; /* Add border to secondary button */  
      }  
  
      .row {  
        justify-content: center; /* Center the row contents */  
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


   <button class="btn btn-primary back-button" style="color: white;" id="backButton">Go Back</button> <!-- Back Button -->  
  
   <div class="container mt-5">  
      <h2 class="text-center mb-4">Click on Your Card to Reveal Your Word</h2>  
      <div id="playerCards" class="row">  
        <!-- Example cards, these will be dynamically generated -->  
        <div class="col-3"> <!-- 4 cards in a row -->  
           <div class="card text-center p-3" id="card1">Card 1</div>  
        </div>  
        <div class="col-3">  
           <div class="card text-center p-3" id="card2">Card 2</div>  
        </div>  
        <div class="col-3">  
           <div class="card text-center p-3" id="card3">Card 3</div>  
        </div>  
        <div class="col-3">  
           <div class="card text-center p-3" id="card4">Card 4</div>  
        </div>  
        <!-- Add more cards as needed -->  
      </div>  
   </div>  
  
   <!-- Modal for back button confirmation -->  
   <div class="modal fade" id="backModal" tabindex="-1" aria-labelledby="backModalLabel" aria-hidden="true">  
      <div class="modal-dialog">  
        <div class="modal-content">  
           <div class="modal-header">  
              <h5 class="modal-title" id="backModalLabel">Choose an Option</h5>  
              <!-- Removed the close button (X) -->  
           </div>  
           <div class="modal-body">  
              <p>Do you want to continue the game or restart it?</p>  
           </div>  
           <div class="modal-footer">  
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Continue</button>  
              <button type="button" class="btn btn-primary" id="restartGame">Restart</button>  
           </div>  
        </div>  
      </div>  
   </div>  
  
   <!-- jQuery library -->  
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  
   <!-- Bootstrap JS for modal functionality -->  
   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>  
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>  
   <?php if ($is_logged_in): ?>  
      <!-- If user is logged in, implement the JS logic here -->  
      <script>  
      $(document).ready(function() {  
  let players = JSON.parse(localStorage.getItem('players') || '[]');  
  let wordsRevealed = 0;  
  let isCardBeingRevealed = false; // Flag to track if a card is currently being revealed  
  
  // Arrays of major and minor words  
  const majorWords = ['Smartphone', 'Computer', 'Television', 'Refrigerator', 'Microwave'];  
  const minorWords = ['Walkie-talkie', 'Calculator', 'Radio', 'Cooler', 'Toaster'];  
  
  // Fetch players from localStorage (whether logged in or guest)  
  if (!players || players.length < 3) {  
   window.location.href = 'start.html'; // Redirect to start if no valid player data  
   return;  
  }  
  
  // Create the player objects  
  players = players.map((player, index) => {  
   return { name: player, role: '', revealed: false };  
  });  
  
  // Create the roles array  
  const roles = ['Mr White', 'Undercover'];  
  for (let i = 2; i < players.length; i++) {  
   roles.push('Normal');  
  }  
  
  // Shuffle the roles array  
  for (let i = roles.length - 1; i > 0; i--) {  
   const j = Math.floor(Math.random() * (i + 1));  
   [roles[i], roles[j]] = [roles[j], roles[i]];  
  }  
  
  // Assign roles to players  
  players.forEach((player, index) => {  
   player.role = roles[index];  
  });  
  
  function assignWords() {  
   // Ensure there are enough players (minimum 4)  
   if (players.length < 4) {  
    window.location.href = 'start.html'; // Redirect to the start page if invalid  
    return;  
   }  
  
   // Check if there is at least one undercover if there are exactly 4 players  
   if (players.length === 4) {  
    const undercoverCount = players.filter(player => player.role === 'Undercover').length;  
    // If there are no undercover players, automatically assign one  
    if (undercoverCount === 0) {  
      if (players[0]) {  
       players[1].role = 'Undercover'; // Assign the second player as Undercover  
      } else {  
       console.error('Players array is not properly populated.');  
       return;  
      }  
    }  
   }  
  
   // Step 1: Assign Mr. White  
   const mrWhiteIndex = players.findIndex(player => player.role === 'Mr White');  
   if (mrWhiteIndex !== -1) {  
    players[mrWhiteIndex].word = ''; // Mr. White does not get a word  
   } else {  
    console.error('No player with role "Mr White" found.');  
   }  
  
   // Step 2: Assign the same major word to all normal players  
   const majorWord = majorWords[Math.floor(Math.random() * majorWords.length)];  
   players.forEach(player => {  
    if (player && player.role === 'Normal') {  
      player.word = majorWord; // All normal players get the same word  
    }  
   });  
  
   // Step 3: Assign the same minor word to all undercover players  
   const minorWord = minorWords[Math.floor(Math.random() * minorWords.length)];  
   players.forEach(player => {  
    if (player && player.role === 'Undercover') {  
      player.word = minorWord; // All undercover players get the same word  
    }  
   });  
  
   // Save players to local storage for the next phase  
   localStorage.setItem('players', JSON.stringify(players));  
  }  
  
  function showPlayerCards() {  
   let cards = '';  
   players.forEach((player, index) => {  
    let playerName = player.name;  
    if (typeof playerName === 'object') {  
      playerName = Object.values(playerName).join(', ');  
    }  
    cards += `<div class="col-4 mb-3">  
      <div class="card p-3 text-center" id="playerCard${index}" data-index="${index}">  
       <h4>${playerName}</h4>  
       <p id="word${index}" class="hidden">${player.role === 'Mr White' ? 'No word (Mr White)' : player.word}</p>  
       <button id="okButton${index}" class="btn btn-primary hidden">OK</button>  
      </div>  
    </div>`;  
   });  
   $('#playerCards').html(cards);  
  
   $('.card').click(function() {  
    const index = $(this).data('index');  
  
    // Check if a card is already being revealed  
    if (isCardBeingRevealed || players[index].revealed) {  
      return; // If a card is being revealed or this card is already revealed, do nothing  
    }  
  
    // Show word and OK button for the clicked card  
    $(`#word${index}`).removeClass('hidden');  
    $(`#okButton${index}`).removeClass('hidden');  
    isCardBeingRevealed = true; // Set the flag indicating a card is being revealed  
  
    // Disable other cards while one card is being revealed  
    $('.card').not(this).addClass('disabled-card');  
  
    // Handle OK button click  
    $(`#okButton${index}`).off('click').on('click', function() {  
      $(`#playerCard${index}`).addClass('disabled-card');  
      $(`#word${index}`).addClass('hidden');  
      $(`#okButton${index}`).addClass('hidden');  
      players[index].revealed = true;  
      wordsRevealed++;  
      isCardBeingRevealed = false; // Reset the flag after the card has been revealed  
  
      // Re-enable the cards that have not been revealed  
      $('.card').not(`#playerCard${index}`).removeClass('disabled-card');  
  
      // Check if all words are revealed  
      if (wordsRevealed === players.length) {  
       localStorage.setItem('players', JSON.stringify(players));  
       setTimeout(() => {  
        window.location.href = 'elimination.html'; // Move to voting after all words are revealed  
       }, 1000);  
      }  
    });  
   });  
  }  
  
  // Assign words based on the roles defined earlier  
  assignWords();  
  showPlayerCards();  
  
  // Handle the back button confirmation modal  
  $('#backButton').on('click', function() {  
   $('#backModal').modal('show');  
  });  
  
  // Restart the game when the Restart button is clicked  
  $('#restartGame').on('click', function() {  
   window.location.href = 'start.html'; // Redirect to the start page  
  });  
});

      </script>  
   <?php else: ?>  
      <!-- If user is NOT logged in, include the external JavaScript file for guest mode -->  
      <script src="./scripts/word_reveal.js"></script>  
   <?php endif; ?>  
</body>  
</html>
