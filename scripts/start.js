$(document).ready(function() {
    $('#startGame').click(function() {
        const playerCount = parseInt($('#playerCount').val(), 10);
        if (playerCount < 4) {
            alert('You need at least 4 players!');
            return;
        }

        // Store the player count in localStorage
        localStorage.setItem('playerCount', playerCount);

        // Redirect to the player setup page
        window.location.href = 'player_setup.php';  // Make sure this matches your actual file path
    });
});
