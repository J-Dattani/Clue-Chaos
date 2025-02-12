document.addEventListener('DOMContentLoaded', function() {
    const gameResult = localStorage.getItem('gameResult');
    document.getElementById('gameResult').textContent = gameResult;

    const scores = JSON.parse(localStorage.getItem('scores') || '{}');
    const players = JSON.parse(localStorage.getItem('players') || '[]');

    // Check if Mr. White won and adjust the scores accordingly
    if (gameResult === "Mr. White wins the game!") {
        scores.mrWhite = 10;  // Mr. White gets 10 points if he wins
        // Assign 2 points to civilians and undercover players
        players.forEach(player => {
            if (player.role !== 'Mr White' && !player.eliminated) {
                if (player.role === 'Normal') {
                    scores.civilian = 2;  // Civilians get 2 points
                } else if (player.role === 'Undercover') {
                    scores.undercover = 2;  // Undercover gets 2 points
                }
            }
        });
    }

    const scoreboardHtml = `
        <h2>Scoreboard</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Role</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Undercover</td>
                    <td>${scores.undercover || 0} 💼</td>
                </tr>
                <tr>
                    <td>Civilians</td>
                    <td>${scores.civilian || 0} 👥</td>
                </tr>
                <tr>
                    <td>Mr. White</td>
                    <td>${scores.mrWhite || 0} 🕵️‍♂️</td>
                </tr>
            </tbody>
        </table>
    `;

    document.querySelector('.result-page').insertAdjacentHTML('beforeend', scoreboardHtml);

    document.getElementById('playAgain').addEventListener('click', function() {
        localStorage.removeItem('players');
        localStorage.removeItem('gameResult');
        localStorage.removeItem('scores');
        window.location.href = 'index.php';
    });
});