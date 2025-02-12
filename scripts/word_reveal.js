$(document).ready(function() {
    let players = JSON.parse(localStorage.getItem('players') || '[]');
    let wordsRevealed = 0;
    let isCardBeingRevealed = false; // Flag to track if a card is currently being revealed

    // Arrays of major and minor words
    const majorWords = ['Smartphone', 'Computer', 'Television', 'Refrigerator', 'Microwave'];
    const minorWords = ['Walkie-talkie', 'Calculator', 'Radio', 'Cooler', 'Toaster'];

    function assignWords() {
        // Ensure there are enough players (minimum 4)
        if (players.length < 4) {
            alert('You need at least 4 players to start the game!');
            window.location.href = 'index.html'; // Redirect to the start page if invalid
            return;
        }

        // Check if there is at least one undercover if there are exactly 4 players
        if (players.length === 4) {
            const undercoverCount = players.filter(player => player.role === 'Undercover').length;
            // If there are no undercover players, automatically assign one
            if (undercoverCount === 0) {
                players[0].role = 'Undercover'; // Assign the first player as Undercover
            }
        }

        // Step 1: Assign Mr. White
        const mrWhiteIndex = players.findIndex(player => player.role === 'Mr White');
        players[mrWhiteIndex].word = ''; // Mr. White does not get a word

        // Step 2: Assign the same major word to all normal players
        const majorWord = majorWords[Math.floor(Math.random() * majorWords.length)];
        players.forEach(player => {
            if (player.role === 'Normal') {
                player.word = majorWord; // All normal players get the same word
            }
        });

        // Step 3: Assign the same minor word to all undercover players
        const minorWord = minorWords[Math.floor(Math.random() * minorWords.length)];
        players.forEach(player => {
            if (player.role === 'Undercover') {
                player.word = minorWord; // All undercover players get the same word
            }
        });

        // Save players to local storage for the next phase
        localStorage.setItem('players', JSON.stringify(players));
    }

    function showPlayerCards() {
        let cards = '';
        players.forEach((player, index) => {
            cards += `<div class="col-4 mb-3">
                <div class="card p-3 text-center" id="playerCard${index}" data-index="${index}">
                    <h4>${player.name}</h4>
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
});
