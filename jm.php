<?php
// Fetch data for JM from the members.json file
$file_path = 'members.json';

if (file_exists($file_path)) {
    $members = json_decode(file_get_contents($file_path), true);
    if ($members === null) {
        $members = [];
    }
} else {
    $members = [];
}

// Find JM's data in the members array
$jmData = null;
foreach ($members as $index => $member) {
    if ($member['name'] === 'JM') {
        $jmData = $member;
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JM's Page</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Styling for Snake Game */
        #snake-game {
            margin-top: 20px;
            padding: 20px;
            background-color: #f4f4f4;
            border-radius: 8px;
            width: 320px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }

        canvas {
            border: 2px solid #000;
            margin-top: 10px;
            background-color: #e0e0e0;
        }

        #score {
            font-size: 20px;
            font-weight: bold;
        }

        #gameStatus {
            margin-top: 10px;
            font-size: 18px;
            font-weight: bold;
        }

        #leaderboard {
            margin-top: 20px;
            text-align: left;
            font-size: 16px;
        }

        #leaderboardList {
            padding-left: 20px;
        }

        #leaderboardList li {
            margin: 5px 0;
        }

        button {
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
        }

        button:hover {
            background-color: #218838;
        }

        /* Name input styling */
        #nameInputContainer {
            display: none;
            margin-top: 20px;
        }

        #nameInput {
            padding: 10px;
            font-size: 14px;
            margin-right: 10px;
        }

        #saveNameButton {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #saveNameButton:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <ul>
            <li>
                <a href="/index.php" class="nav-link active">
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
        <h1>JM's Page</h1>

        <!-- Editable Form for JM -->
        <?php if ($jmData): ?>
            <form action="save_member.php" method="post">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="name" value="JM">
                
                <label for="blockInput">Block:</label>
                <input type="text" name="block" id="blockInput" value="<?= htmlspecialchars($jmData['block']) ?>" required>

                <button type="submit">Save Changes</button>
            </form>
        <?php else: ?>
            <p>No data available for JM.</p>
        <?php endif; ?>
    </div>

    <!-- Snake Game Section -->
    <div id="snake-game">
        <h2>Snake Game</h2>
        <canvas id="snakeCanvas" width="300" height="300"></canvas>
        <p id="score">Score: 0</p>
        <p id="gameStatus"></p>
        <button id="restartButton" onclick="resetGame()">Restart Game</button>

        <!-- Name input section for leaderboard -->
        <div id="nameInputContainer">
            <input type="text" id="nameInput" placeholder="Enter your name" />
            <button id="saveNameButton" onclick="saveScore()">Save Score</button>
        </div>
    </div>

    <div id="leaderboard">
        <h3>Leaderboard</h3>
        <ul id="leaderboardList">
            <!-- Leaderboard will be populated here -->
        </ul>
    </div>

    <script>
        const canvas = document.getElementById('snakeCanvas');
        const ctx = canvas.getContext('2d');

        const grid = 15;
        let count = 0;
        let score = 0;
        let snake = [{x: 5 * grid, y: 5 * grid}];
        let direction = 'RIGHT';
        let food = {x: 10 * grid, y: 10 * grid};
        let gameOver = false;
        let gameSpeed = 15; // Initial game speed

        // Load leaderboard from localStorage
        let leaderboard = JSON.parse(localStorage.getItem('leaderboard')) || [];

        // Add sound effects
        const eatSound = new Audio('eat.mp3');
        const gameOverSound = new Audio('gameover.mp3');

        function gameLoop() {
            if (gameOver) {
                document.getElementById('gameStatus').textContent = 'Game Over! Press R to Restart';
                showNameInput();
                gameOverSound.play();
                return;
            }

            setTimeout(function() {
                count++;
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                moveSnake();
                drawSnake();
                drawFood();
                checkCollisions();
                document.getElementById('score').textContent = `Score: ${score}`;
                if (score >= 10) {
                    gameSpeed = 20; // Increase speed as score increases
                }
                gameLoop();
            }, 1000 / gameSpeed);
        }

        function moveSnake() {
            const head = {x: snake[0].x, y: snake[0].y};

            if (direction === 'UP') head.y -= grid;
            if (direction === 'DOWN') head.y += grid;
            if (direction === 'LEFT') head.x -= grid;
            if (direction === 'RIGHT') head.x += grid;

            snake.unshift(head);

            if (head.x === food.x && head.y === food.y) {
                food = {
                    x: Math.floor(Math.random() * (canvas.width / grid)) * grid,
                    y: Math.floor(Math.random() * (canvas.height / grid)) * grid
                };
                score++;
                eatSound.play(); // Play eat sound
            } else {
                snake.pop();
            }
        }

        function drawSnake() {
            ctx.fillStyle = 'green';
            for (let i = 0; i < snake.length; i++) {
                ctx.fillRect(snake[i].x, snake[i].y, grid, grid);
            }
        }

        function drawFood() {
            ctx.fillStyle = 'red';
            ctx.fillRect(food.x, food.y, grid, grid);
        }

        function checkCollisions() {
            const head = snake[0];

            // Check wall collisions
            if (head.x < 0 || head.x >= canvas.width || head.y < 0 || head.y >= canvas.height) {
                gameOver = true;
            }

            // Check self-collision
            for (let i = 1; i < snake.length; i++) {
                if (snake[i].x === head.x && snake[i].y === head.y) {
                    gameOver = true;
                }
            }
        }

        function changeDirection(event) {
            if (event.key === 'ArrowUp' && direction !== 'DOWN') {
                direction = 'UP';
            }
            if (event.key === 'ArrowDown' && direction !== 'UP') {
                direction = 'DOWN';
            }
            if (event.key === 'ArrowLeft' && direction !== 'RIGHT') {
                direction = 'LEFT';
            }
            if (event.key === 'ArrowRight' && direction !== 'LEFT') {
                direction = 'RIGHT';
            }
            if (event.key === 'r' || event.key === 'R') {
                resetGame();
            }
        }

        function resetGame() {
            snake = [{x: 5 * grid, y: 5 * grid}];
            direction = 'RIGHT';
            food = {x: 10 * grid, y: 10 * grid};
            score = 0;
            gameOver = false;
            gameSpeed = 15; // Reset speed
            document.getElementById('gameStatus').textContent = '';
            document.getElementById('nameInputContainer').style.display = 'none';
            gameLoop();
        }

        function showNameInput() {
            // Show the name input form when the game is over
            document.getElementById('nameInputContainer').style.display = 'block';
        }

        function saveScore() {
            const playerName = document.getElementById('nameInput').value.trim();
            if (playerName) {
                leaderboard.push({ name: playerName, score: score });
                leaderboard.sort((a, b) => b.score - a.score); // Sort by score
                localStorage.setItem('leaderboard', JSON.stringify(leaderboard));

                updateLeaderboard();
                resetGame();
            }
        }

        function updateLeaderboard() {
            const leaderboardList = document.getElementById('leaderboardList');
            leaderboardList.innerHTML = '';
            leaderboard.slice(0, 10).forEach(function(player) {
                const listItem = document.createElement('li');
                listItem.textContent = `${player.name}: ${player.score}`;
                leaderboardList.appendChild(listItem);
            });
        }

        // Initialize game
        window.addEventListener('keydown', changeDirection);
        gameLoop();
    </script>
</body>
</html>
