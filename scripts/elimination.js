document.addEventListener('DOMContentLoaded', function() {
    // Initialize players and scores
    let players = JSON.parse(localStorage.getItem('players') || '[]');
    let scores = JSON.parse(localStorage.getItem('scores') || '{"civilian": 0, "undercover": 0, "mrWhite": 0}');

    function startVoting() {
        let voteOptions = '';
        players.forEach((player, index) => {
            voteOptions += `<div class="player-card ${player.eliminated ? 'eliminated' : ''}" data-index="${index}" onclick="selectPlayer(${index})">
                <div class="player-name">${player.name}</div>
                ${player.eliminated ? '<div style="color: red;">(Eliminated)</div>' : ''}
            </div>`;
        });
        document.getElementById('voteOptions').innerHTML = voteOptions;
    }

    window.selectPlayer = function(index) {
        if (players[index].eliminated) return; // Prevent selecting eliminated players
        document.querySelectorAll('.player-card').forEach(card => card.classList.remove('selected'));
        document.querySelector(`.player-card[data-index="${index}"]`).classList.add('selected');
    };

    document.getElementById('submitVote').addEventListener('click', function() {
        const selectedCard = document.querySelector('.player-card.selected');
        if (!selectedCard) {
            alert('Please select a player to eliminate!');
            return;
        }
        const votedIndex = parseInt(selectedCard.dataset.index);
        eliminatePlayer(votedIndex);
    });

    function eliminatePlayer(index) {
        players[index].eliminated = true;
        let eliminatedPlayer = players[index];
        let roleMessage = '';
        
        if (eliminatedPlayer.role === 'Normal') {
            roleMessage = `${eliminatedPlayer.name} was a civilian (Normal player).`;
            scores.civilian += 2;  // Civilian eliminated, get lower points
        } else if (eliminatedPlayer.role === 'Undercover') {
            roleMessage = `${eliminatedPlayer.name} was an Undercover!`;
            scores.undercover += 5;  // Undercover eliminated, get higher points
        } else if (eliminatedPlayer.role === 'Mr White') {
            const civilianWord = players.find(player => player.role === 'Normal').word;
            let mrWhiteGuess = prompt(`${eliminatedPlayer.name}, you are Mr. White! Guess the civilian word to win:`);
    
            if (mrWhiteGuess && mrWhiteGuess.trim().toLowerCase() === civilianWord.toLowerCase()) {
                // Correct guess, Mr. White wins
                alert("Congratulations, Mr. White guessed the word correctly! Mr. White wins!");
                
                // Set Mr. White's score to 10 and all others to 2
                scores.mrWhite = 10;
                scores.civilian = 2;
                scores.undercover = 2;
    
                // Save the updated scores to localStorage
                localStorage.setItem('scores', JSON.stringify(scores));
    
                // End the game after scoring
                endGame("Mr. White wins the game!");
                return;
            } else {
                // Incorrect guess, game over
                alert("Incorrect guess! Mr. White is eliminated.");
                
                // Mr. White gets 0 points, others get their respective scores
                scores.mrWhite = 0;
                scores.civilian = 5; // Civilians get 5 points (higher points)
                scores.undercover = 5; // Undercover players also get higher points
    
                // Save the updated scores to localStorage
                localStorage.setItem('scores', JSON.stringify(scores));
    
                // End the game with the result
                endGame("Mr. White's guess was incorrect. Game Over!");
                return;
            }
        }
    
        alert(`${eliminatedPlayer.name} has been eliminated! ${roleMessage}`);
        
        // Save the updated players and scores to localStorage
        localStorage.setItem('players', JSON.stringify(players));
        localStorage.setItem('scores', JSON.stringify(scores)); // Save scores to localStorage
        console.log('Current scores:', scores);  // This will help debug the scores
        startVoting();
        checkGameEnd();
    }
     

    function checkGameEnd() {
        const remainingUndercover = players.filter(p => !p.eliminated && p.role === 'Undercover').length;
        const remainingNormal = players.filter(p => !p.eliminated && p.role === 'Normal').length;
        const remainingPlayers = players.filter(p => !p.eliminated);

        // Game ends if no civilians are left and only Mr. White or Undercover remain
        if (remainingNormal === 0) {
            if (remainingUndercover > 0) {
                // If there are still undercover players, game continues, but check if only one remains
                if (remainingPlayers.length === 1 && remainingUndercover === 1) {
                    endGame('Undercover wins!');  // Only Undercover left, game ends
                }
            } else if (remainingPlayers.length === 1) {
                endGame('Mr. White wins!');  // Only Mr. White is left, game ends
            }
        } else if (remainingNormal === 1 && remainingUndercover === 1) {
            // If only 1 civilian and 1 undercover are left, undercover wins
            endGame('Undercover wins!');  // Undercover wins with full points
        }
    }

    function endGame(result) {
        localStorage.setItem('gameResult', result);
        window.location.href = 'results.php';
    }

    startVoting();
});