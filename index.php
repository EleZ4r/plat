<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Management - Home</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Additional styling for the Tetris Game */
        #gameCanvas {
            border: 2px solid black;
            margin-top: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        #score {
            text-align: center;
            font-size: 24px;
            margin-top: 20px;
        }
        #pauseBtn, #restartBtn {
            display: block;
            margin: 20px auto;
            font-size: 18px;
            padding: 10px;
            cursor: pointer;
        }
        #gameOverMessage {
            text-align: center;
            font-size: 36px;
            color: red;
            display: none;
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
        <h1>Member Management - Home</h1>
        <!-- Tetris Game Canvas -->
        <canvas id="gameCanvas" width="300" height="600"></canvas>

        <!-- Score Display -->
        <div id="score">
            Score: <span id="scoreValue">0</span>
        </div>

        <!-- Game Over Message -->
        <div id="gameOverMessage">
            Game Over! <br>
            <button id="restartBtn">Restart</button>
        </div>

        <!-- Pause Button -->
        <button id="pauseBtn">Pause</button>
    </div>

    <!-- Particles.js Container -->
    <div id="particles-js"></div>

    <script src="particles-config.js"></script>

    <!-- Tetris Game Script -->
    <script>
        const canvas = document.getElementById("gameCanvas");
        const ctx = canvas.getContext("2d");

        const tetrominoes = [
            [[1, 1, 1, 1]], // I
            [[1, 1], [1, 1]], // O
            [[1, 1, 0], [0, 1, 1]], // S
            [[0, 1, 1], [1, 1, 0]], // Z
            [[1, 0, 0], [1, 1, 1]], // L
            [[0, 0, 1], [1, 1, 1]], // J
            [[1, 1, 1], [0, 1, 0]] // T
        ];

        const colors = ["cyan", "yellow", "green", "red", "blue", "orange", "purple"];
        const rowCount = 20;
        const columnCount = 10;
        let board = Array.from({ length: rowCount }, () => Array(columnCount).fill(null));

        let currentPiece = generateTetromino();
        let currentPosition = { x: 4, y: 0 };
        let score = 0;
        let gamePaused = false;
        let gameOver = false;
        let gameSpeed = 500; // Game speed in milliseconds
        let gameInterval;

        // Generate new Tetromino
        function generateTetromino() {
            const index = Math.floor(Math.random() * tetrominoes.length);
            return {
                shape: tetrominoes[index],
                color: colors[index]
            };
        }

        // Draw the game board
        function drawBoard() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            for (let y = 0; y < rowCount; y++) {
                for (let x = 0; x < columnCount; x++) {
                    const cell = board[y][x];
                    if (cell) {
                        ctx.fillStyle = cell;
                        ctx.fillRect(x * 30, y * 30, 30, 30);
                        ctx.strokeStyle = "black";
                        ctx.strokeRect(x * 30, y * 30, 30, 30);
                    }
                }
            }
        }

        // Draw the current Tetromino piece
        function drawPiece() {
            for (let y = 0; y < currentPiece.shape.length; y++) {
                for (let x = 0; x < currentPiece.shape[y].length; x++) {
                    if (currentPiece.shape[y][x]) {
                        ctx.fillStyle = currentPiece.color;
                        ctx.fillRect((currentPosition.x + x) * 30, (currentPosition.y + y) * 30, 30, 30);
                        ctx.strokeStyle = "black";
                        ctx.strokeRect((currentPosition.x + x) * 30, (currentPosition.y + y) * 30, 30, 30);
                    }
                }
            }
        }

        // Handle piece rotation
        function rotatePiece() {
            const newShape = currentPiece.shape[0].map((_, index) =>
                currentPiece.shape.map(row => row[index])
            );
            const oldShape = currentPiece.shape;
            currentPiece.shape = newShape;
            if (collision()) {
                currentPiece.shape = oldShape;
            }
        }

        // Check for collision
        function collision() {
            for (let y = 0; y < currentPiece.shape.length; y++) {
                for (let x = 0; x < currentPiece.shape[y].length; x++) {
                    if (currentPiece.shape[y][x] &&
                        (board[currentPosition.y + y] && board[currentPosition.y + y][currentPosition.x + x]) !== null
                    ) {
                        return true;
                    }
                }
            }
            return false;
        }

        // Drop the piece
        function dropPiece() {
            currentPosition.y++;
            if (collision()) {
                currentPosition.y--;
                placePiece();
                clearLines();
                updateScore();
            }
        }

        // Place the piece in the board
        function placePiece() {
            for (let y = 0; y < currentPiece.shape.length; y++) {
                for (let x = 0; x < currentPiece.shape[y].length; x++) {
                    if (currentPiece.shape[y][x]) {
                        board[currentPosition.y + y][currentPosition.x + x] = currentPiece.color;
                    }
                }
            }
            currentPiece = generateTetromino();
            currentPosition = { x: 4, y: 0 };
            if (collision()) {
                gameOverFunc();
            }
        }

        // Clear full lines
        function clearLines() {
            for (let y = rowCount - 1; y >= 0; y--) {
                if (board[y].every(cell => cell !== null)) {
                    board.splice(y, 1);
                    board.unshift(Array(columnCount).fill(null));
                    score += 100; // Increase score for every line cleared
                    y++; // Stay on the same line after clearing
                }
            }
        }

        // Update the score display
        function updateScore() {
            document.getElementById("scoreValue").textContent = score;
        }

        // Display game over message
        function gameOverFunc() {
            clearInterval(gameInterval);
            gameOver = true;
            document.getElementById("gameOverMessage").style.display = "block";
        }

        // Restart the game
        function restartGame() {
            board = Array.from({ length: rowCount }, () => Array(columnCount).fill(null));
            score = 0;
            updateScore();
            currentPiece = generateTetromino();
            currentPosition = { x: 4, y: 0 };
            gameOver = false;
            document.getElementById("gameOverMessage").style.display = "none";
            startGame();
        }

        // Start or resume the game
        function startGame() {
            gameInterval = setInterval(gameLoop, gameSpeed);
        }

        // Game loop to update game state
        function gameLoop() {
            if (gamePaused || gameOver) return;
            drawBoard();
            drawPiece();
            dropPiece();
        }

        // Pause or resume the game
        function togglePause() {
            if (gamePaused) {
                gamePaused = false;
                startGame();
            } else {
                gamePaused = true;
                clearInterval(gameInterval);
            }
        }

        // Event listeners for user input
        document.addEventListener("keydown", function (e) {
            if (gameOver) return;

            if (e.key === "ArrowLeft") {
                currentPosition.x--;
                if (collision()) currentPosition.x++;
            }
            if (e.key === "ArrowRight") {
                currentPosition.x++;
                if (collision()) currentPosition.x--;
            }
            if (e.key === "ArrowDown") {
                currentPosition.y++;
                if (collision()) currentPosition.y--;
            }
            if (e.key === "ArrowUp") {
                rotatePiece();
            }
        });

        // Button actions
        document.getElementById("pauseBtn").addEventListener("click", togglePause);
        document.getElementById("restartBtn").addEventListener("click", restartGame);

        // Start the game initially
        startGame();
    </script>
</body>
</html>
