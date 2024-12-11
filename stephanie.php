<?php
// Fetch data for Stephanie
$file_path = 'members.json';

if (file_exists($file_path)) {
    $members = json_decode(file_get_contents($file_path), true);
    if ($members === null) {
        $members = [];
    }
} else {
    $members = [];
}

// Find Stephanie in the members array
$stephanieData = null;
foreach ($members as $index => $member) {
    if ($member['name'] === 'Stephanie') {
        $stephanieData = $member;
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stephanie's Page</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Word Scramble Game Styling */
        .game-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 50px;
        }

        .game-header {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .scrambled-word {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .input-container {
            margin-bottom: 20px;
        }

        .input-container input {
            padding: 10px;
            font-size: 18px;
            width: 250px;
        }

        .feedback {
            font-size: 20px;
            font-weight: bold;
        }

        .score {
            font-size: 18px;
            margin-top: 20px;
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
        <h1>Stephanie's Page</h1>

        <!-- Editable Form for Stephanie -->
        <?php if ($stephanieData): ?>
            <form action="save_member.php" method="post">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="name" value="Stephanie">
                
                <label for="blockInput">Block:</label>
                <input type="text" name="block" id="blockInput" value="<?= htmlspecialchars($stephanieData['block']) ?>" required>

                <button type="submit">Save Changes</button>
            </form>
        <?php else: ?>
            <p>No data available for Stephanie.</p>
        <?php endif; ?>

        <!-- Word Scramble Challenge Section -->
        <div class="game-container">
            <h2 class="game-header">Word Scramble Challenge</h2>

            <div class="scrambled-word" id="scrambled-word">Loading...</div>

            <div class="input-container">
                <label for="word-guess">Guess the word:</label>
                <input type="text" id="word-guess" placeholder="Enter your guess">
            </div>

            <button onclick="checkGuess()">Submit Guess</button>

            <p id="feedback" class="feedback"></p>

            <p id="score" class="score">Score: 0</p>
        </div>
    </div>

    <!-- Particles.js Container -->
    <div id="particles.js"></div>

    <script src="particles-config.js"></script>
    <script>
        const words = ["javascript", "html", "css", "chongke", "stephanie", "python", "ruby", "javascript"];
        let scrambledWord = "";
        let currentWord = "";
        let score = 0;

        // Function to scramble a word
        function scrambleWord(word) {
            const scrambled = word.split('').sort(() => Math.random() - 0.5).join('');
            return scrambled;
        }

        // Function to start a new round with a new word
        function startNewRound() {
            const randomIndex = Math.floor(Math.random() * words.length);
            currentWord = words[randomIndex];
            scrambledWord = scrambleWord(currentWord);
            document.getElementById('scrambled-word').textContent = scrambledWord;
            document.getElementById('word-guess').value = '';  // Clear input
            document.getElementById('feedback').textContent = '';  // Clear feedback
        }

        // Check the user's guess
        function checkGuess() {
            const guess = document.getElementById('word-guess').value.trim().toLowerCase();

            if (guess === currentWord) {
                score++;
                document.getElementById('feedback').textContent = "Correct! Well done.";
                document.getElementById('feedback').style.color = "green";
                document.getElementById('score').textContent = "Score: " + score;
                startNewRound();  // Start a new round
            } else {
                document.getElementById('feedback').textContent = "Incorrect, try again.";
                document.getElementById('feedback').style.color = "red";
            }
        }

        // Start the first round when the page loads
        window.onload = startNewRound;
    </script>
</body>
</html>
