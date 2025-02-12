$(document).ready(function() {
    // Retrieve player count from localStorage
    const playerCount = parseInt(localStorage.getItem('playerCount'), 10);
    
    // Validate player count
    if (!playerCount || playerCount < 3) {
        alert('Invalid number of players. Redirecting to the start page.');
        window.location.href = 'start.html';
        return;
    }

    // Generate input fields for each player
    const playerInputsContainer = $('#playerInputs');
    for (let i = 1; i <= playerCount; i++) {
        playerInputsContainer.append(`
            <div class="mb-3">
                <label for="player${i}" class="form-label">Player ${i} Name:</label>
                <input type="text" class="form-control playerName" id="player${i}" placeholder="Enter Player ${i} Name" required>
            </div>
        `);
    }

    // Remove the undercover selection (no longer needed)
    // No need to display the undercover container, so it's always hidden.
    $('#undercoverContainer').addClass('hidden');

    // Handle the "Set Up Players" button click
    $('#setupPlayers').click(function(event) {
        event.preventDefault();

        // Collect player data
        let players = $('.playerName').map(function() {
            const playerName = $(this).val().trim();
            if (playerName === "") {
                alert("Player names cannot be empty. Please provide a name for each player.");
                throw new Error("Empty player name.");
            }
            return {
                name: playerName,
                role: 'Normal',  // Default role
                word: '',
                revealed: false,
                eliminated: false
            };
        }).get();

        // Automatically determine the number of undercovers based on the player count
        let undercoverCount = 0;
        if (playerCount <= 7) {
            undercoverCount = 1; // Only 1 undercover if player count is 7 or less
        } else if (playerCount > 7 && playerCount <= 15) {
            undercoverCount = 2; // 2 undercovers if player count is between 8 and 15
        }

        // Step 1: Assign roles
        let undercoverIndices = [];
        while (undercoverIndices.length < undercoverCount) {
            const index = Math.floor(Math.random() * players.length);
            if (!undercoverIndices.includes(index)) {
                undercoverIndices.push(index);
                players[index].role = 'Undercover';
            }
        }

        // Assign Mr. White
        const mrWhiteIndex = Math.floor(Math.random() * players.length);
        // Ensure Mr. White is not an Undercover player
        if (!undercoverIndices.includes(mrWhiteIndex)) {
            players[mrWhiteIndex].role = 'Mr White';
            players[mrWhiteIndex].word = ''; // Mr. White does not get a word
        } else {
            // If Mr. White was randomly selected as an undercover, reassign
            players[mrWhiteIndex].role = 'Normal';
            const newMrWhiteIndex = undercoverIndices.pop(); // Take last undercover index to replace
            players[newMrWhiteIndex].role = 'Mr White'; // Assign Mr. White role
            players[newMrWhiteIndex].word = ''; // Mr. White gets no word
        }

        // Ensure only one Mr. White and no duplicates
        players.forEach((player, index) => {
            if (undercoverIndices.includes(index)) {
                player.role = 'Undercover'; // Confirm undercover players
            } else if (player.role === 'Normal') {
                player.role = 'Normal'; // Ensure non-undercover roles
            }
        });

        // Step 2: Assign words
        const majorWords = ['Smartphone', 'Computer', 'Television', 'Refrigerator', 'Microwave'];
        const minorWords = ['Walkie-talkie', 'Calculator', 'Radio', 'Cooler', 'Toaster'];

        // Assign a single major word for all normal players
        const majorWord = majorWords[Math.floor(Math.random() * majorWords.length)];
        players.forEach(player => {
            if (player.role === 'Normal') {
                player.word = majorWord; // Assign same major word to all normal players
            }
        });

        // Select unique minor words for undercover players
        const selectedMinorWords = [];
        while (selectedMinorWords.length < undercoverCount) {
            const word = minorWords[Math.floor(Math.random() * minorWords.length)];
            if (!selectedMinorWords.includes(word)) {
                selectedMinorWords.push(word);
            }
        }

        // Assign words to undercover players
        players.forEach(player => {
            if (player.role === 'Undercover') {
                player.word = selectedMinorWords.pop(); // Get a unique minor word for undercover
            }
        });

        // Store player data in localStorage and proceed to the word-reveal page
        localStorage.setItem('players', JSON.stringify(players));
        window.location.href = 'word_reveal.php';
    });
});
