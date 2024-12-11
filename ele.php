<?php
// Fetch data for the specific member (e.g., Ele) from JSON
$file_path = 'members.json';

if (file_exists($file_path)) {
    $members = json_decode(file_get_contents($file_path), true);
    if ($members === null) {
        $members = [];
    }
} else {
    $members = [];
}

// Find Ele in the members array (assuming Ele's data exists)
$eleData = null;
foreach ($members as $index => $member) {
    if ($member['name'] === 'Ele') {
        $eleData = $member;
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ele's Page</title>
    <link rel="stylesheet" href="styles.css">
    
    <style>
        /* General Page Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        nav {
            background-color: #333;
            padding: 15px 0;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
        }

        nav ul li {
            display: inline;
            margin-right: 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
        }

        nav ul li a:hover {
            color: #4CAF50;
        }

        /* Container for content */
        .container {
            margin-top: 30px;
        }

        /* Editable Form */
        #form-container {
            margin-bottom: 20px;
        }

        #form-container input {
            padding: 8px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Memory Game Styles */
        #memory-game {
            display: grid;
            grid-template-columns: repeat(4, 100px);
            grid-gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }

        #memory-game button {
            width: 100px;
            height: 100px;
            font-size: 24px;
            background-color: #f4f4f4;
            border: 1px solid #ddd;
            cursor: pointer;
            text-align: center;
            border-radius: 5px;
            position: relative;
            transform: scale(1);
            transition: transform 0.2s ease, background-color 0.2s ease;
        }

        #memory-game button.flipped {
            background-color: #4CAF50;
            color: white;
            transform: rotateY(180deg);
        }

        #memory-game button.matched {
            background-color: #45a049;
            color: white;
            transform: scale(1.1);
        }

        /* Game Status and Message */
        #game-message {
            margin-top: 20px;
            font-weight: bold;
            font-size: 18px;
        }

        /* Stats Container */
        #game-stats {
            font-size: 18px;
            margin-top: 20px;
            color: #333;
        }

    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <ul>
            <li>
                <a href="index.php" class="nav-link active">
                    <img src="home.png" alt="Home" class="nav-img"> Home
                </a>
            </li>
            <li>
                <a href="stephanie.php" class="nav-link">
                    <img src="stephanie (2).jpg" alt="Stephanie" class="nav-img"> Stephanie
                </a>
            </li>
            <li>
                <a href="gabriel.php" class="nav-link">
                    <img src="gabriel (2).jpg" alt="Gabriel" class="nav-img"> Gabriel
                </a>
            </li>
            <li>
                <a href="jm.php" class="nav-link">
                    <img src="john michael.jpg" alt="JM" class="nav-img"> JM
                </a>
            </li>
            <li>
                <a href="ele.php" class="nav-link">
                    <img src="ele.png" alt="Ele" class="nav-img"> Ele
                </a>
            </li>
        </ul>
    </nav>
    <div class="container">
        <h1>Ele's Page</h1>
        
        <!-- Editable Form for Ele -->
        <?php if ($eleData): ?>
            <div id="form-container">
                <form action="save_member.php" method="post">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="name" value="Ele">
                    
                    <label for="blockInput">Block:</label>
                    <input type="text" name="block" id="blockInput" value="<?= htmlspecialchars($eleData['block']) ?>" required>

                    <button type="submit">Save Changes</button>
                </form>
            </div>
        <?php else: ?>
            <p>No data available for Ele.</p>
        <?php endif; ?>

        <!-- Game Section: Memory Match -->
        <div id="game-container">
            <h2>Memory Match Game</h2>
            <p>Match the pairs of cards:</p>
            <div id="memory-game">
                <!-- Memory cards will be generated here -->
            </div>
            <div id="game-stats">
                <p>Moves: <span id="move-counter">0</span></p>
                <p>Time: <span id="timer">00:00</span></p>
            </div>
            <p id="game-message"></p>
            <button onclick="resetGame()">Restart Game</button>
        </div>
    </div>

    <!-- Particles.js Container -->
    <div id="particles-js"></div>
    <script src="particles-config.js"></script>

    <script>
        // Memory Match Game Logic
        const cards = [
            'A', 'A', 'B', 'B', 'C', 'C', 'D', 'D', 'E', 'E', 'F', 'F', 'G', 'G'
        ];
        let flippedCards = [];
        let matchedCards = [];
        let moveCounter = 0;
        let gameOver = false;
        let timer = 0;
        let timerInterval;

        // Shuffle the cards
        function shuffleCards() {
            for (let i = cards.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [cards[i], cards[j]] = [cards[j], cards[i]];
            }
        }

        // Start the timer when the game begins
        function startTimer() {
            if (!timerInterval) {
                timerInterval = setInterval(() => {
                    timer++;
                    const minutes = Math.floor(timer / 60);
                    const seconds = timer % 60;
                    document.getElementById('timer').textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                }, 1000);
            }
        }

        // Create the game board
        function createBoard() {
            const gameBoard = document.getElementById('memory-game');
            gameBoard.innerHTML = '';
            cards.forEach((card, index) => {
                const button = document.createElement('button');
                button.dataset.card = card;
                button.onclick = () => flipCard(button, index);
                gameBoard.appendChild(button);
            });
        }

        // Flip the card
        function flipCard(button, index) {
            if (gameOver || button.classList.contains('flipped') || flippedCards.length === 2) return;

            button.textContent = button.dataset.card;
            button.classList.add('flipped');
            flippedCards.push({ button, card: button.dataset.card, index });
            moveCounter++;
            document.getElementById('move-counter').textContent = moveCounter;

            if (flippedCards.length === 2) {
                checkMatch();
            }
        }

        // Check if the flipped cards match
        function checkMatch() {
            const [first, second] = flippedCards;
            if (first.card === second.card) {
                matchedCards.push(first.card);
                first.button.classList.add('matched');
                second.button.classList.add('matched');
                flippedCards = [];
                if (matchedCards.length === cards.length / 2) {
                    document.getElementById('game-message').textContent = "Congratulations! You matched all pairs!";
                    gameOver = true;
                    clearInterval(timerInterval);
                }
            } else {
                setTimeout(() => {
                    first.button.textContent = '';
                    second.button.textContent = '';
                    first.button.classList.remove('flipped');
                    second.button.classList.remove('flipped');
                    flippedCards = [];
                }, 1000);
            }
        }

        // Reset the game
        function resetGame() {
            matchedCards = [];
            flippedCards = [];
            moveCounter = 0;
            gameOver = false;
            timer = 0;
            document.getElementById('move-counter').textContent = moveCounter;
            document.getElementById('game-message').textContent = '';
            document.getElementById('timer').textContent = '00:00';
            shuffleCards();
            createBoard();
            clearInterval(timerInterval);
            timerInterval = null;
            startTimer();
        }

        // Initialize the game
        shuffleCards();
        createBoard();
        startTimer();
    </script>

    <script src="script.js"></script>
</body>
</html>
