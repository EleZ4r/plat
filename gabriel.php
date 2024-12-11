<?php
// Fetch data for Gabriel from the members.json file
$file_path = 'members.json';

if (file_exists($file_path)) {
    $members = json_decode(file_get_contents($file_path), true);
    if ($members === null) {
        $members = [];
    }
} else {
    $members = [];
}

// Find Gabriel's data in the members array
$gabrielData = null;
foreach ($members as $index => $member) {
    if ($member['name'] === 'Gabriel') {
        $gabrielData = $member;
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gabriel's Page</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Trivia Quiz Game Styles */
        .quiz-container {
            text-align: center;
            margin-top: 30px;
        }

        .question {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .options {
            list-style-type: none;
            padding: 0;
        }

        .option {
            background-color: #f0f0f0;
            padding: 10px;
            margin: 10px 0;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .option:hover {
            background-color: #c9c9c9;
        }

        .score-container {
            text-align: center;
            margin-top: 20px;
        }

        .result-container {
            margin-top: 30px;
        }

        .timer {
            font-size: 18px;
            margin-top: 20px;
        }

        #nextButton {
            display: none;
        }

        #restartButton {
            display: none;
            padding: 10px;
            margin-top: 20px;
            cursor: pointer;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
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
        <h1>Gabriel's Page</h1>

        <!-- Editable Form for Gabriel -->
        <?php if ($gabrielData): ?>
            <form action="save_member.php" method="post">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="name" value="Gabriel">
                
                <label for="blockInput">Block:</label>
                <input type="text" name="block" id="blockInput" value="<?= htmlspecialchars($gabrielData['block']) ?>" required>

                <button type="submit">Save Changes</button>
            </form>
        <?php else: ?>
            <p>No data available for Gabriel.</p>
        <?php endif; ?>

        <!-- Trivia Quiz Game Section -->
        <div class="quiz-container">
            <h2>Trivia Quiz Game</h2>

            <div class="question" id="question">Loading question...</div>
            
            <ul class="options" id="options">
                <!-- Options will be populated dynamically -->
            </ul>

            <button id="nextButton" onclick="nextQuestion()">Next Question</button>

            <!-- Score Section -->
            <div class="score-container">
                <p id="score">Score: 0</p>
            </div>

            <!-- Timer -->
            <div class="timer" id="timer">Time Remaining: 10s</div>

            <button id="restartButton" onclick="restartQuiz()">Restart Quiz</button>
        </div>
    </div>

    <script>
        // 100 Trivia questions and answers
        const questions = [
            { question: "What is the capital of France?", options: ["Berlin", "Madrid", "Paris", "Rome"], correct: 2 },
            { question: "Which planet is known as the Red Planet?", options: ["Earth", "Mars", "Jupiter", "Saturn"], correct: 1 },
            { question: "What is the largest ocean on Earth?", options: ["Atlantic Ocean", "Indian Ocean", "Arctic Ocean", "Pacific Ocean"], correct: 3 },
            { question: "Who wrote 'Harry Potter'?", options: ["J.R.R. Tolkien", "George R.R. Martin", "J.K. Rowling", "Stephen King"], correct: 2 },
            { question: "What is the smallest country in the world?", options: ["Monaco", "San Marino", "Vatican City", "Liechtenstein"], correct: 2 },
            { question: "What is the chemical symbol for water?", options: ["O2", "H2O", "CO2", "H2"], correct: 1 },
            { question: "Who painted the Mona Lisa?", options: ["Vincent van Gogh", "Pablo Picasso", "Leonardo da Vinci", "Claude Monet"], correct: 2 },
            { question: "What is the largest planet in our solar system?", options: ["Earth", "Jupiter", "Saturn", "Mars"], correct: 1 },
            { question: "What year did World War II end?", options: ["1941", "1945", "1950", "1939"], correct: 1 },
            { question: "Which country is known as the Land of the Rising Sun?", options: ["China", "Japan", "South Korea", "India"], correct: 1 },
            { question: "Who is the father of modern physics?", options: ["Isaac Newton", "Albert Einstein", "Galileo Galilei", "Nikola Tesla"], correct: 1 },
            { question: "What is the square root of 64?", options: ["6", "7", "8", "9"], correct: 2 },
            { question: "What is the smallest prime number?", options: ["1", "2", "3", "5"], correct: 1 },
            // Add more questions here, making the total 100...
        ];

        // Shuffle and pick 10 random questions
        let selectedQuestions = [];
        function shuffleQuestions() {
            const shuffled = [...questions].sort(() => Math.random() - 0.5);
            selectedQuestions = shuffled.slice(0, 10);
        }

        let currentQuestionIndex = 0;
        let score = 0;
        let timerInterval;
        let timeRemaining = 10;

        // Function to load the question and options
        function loadQuestion() {
            const questionObj = selectedQuestions[currentQuestionIndex];
            const questionElement = document.getElementById('question');
            const optionsElement = document.getElementById('options');
            const nextButton = document.getElementById('nextButton');
            const timerElement = document.getElementById('timer');

            // Reset timer and show it
            timeRemaining = 10;
            timerElement.textContent = `Time Remaining: ${timeRemaining}s`;
            clearInterval(timerInterval);
            timerInterval = setInterval(updateTimer, 1000);

            // Display the question
            questionElement.textContent = questionObj.question;

            // Clear the previous options
            optionsElement.innerHTML = '';

            // Display options as clickable items
            questionObj.options.forEach((option, index) => {
                const optionElement = document.createElement('li');
                optionElement.classList.add('option');
                optionElement.textContent = option;
                optionElement.onclick = () => checkAnswer(index);
                optionsElement.appendChild(optionElement);
            });

            nextButton.style.display = 'none'; // Hide next button until answer is selected
        }

        // Function to update the timer
        function updateTimer() {
            const timerElement = document.getElementById('timer');
            timeRemaining--;
            if (timeRemaining <= 0) {
                clearInterval(timerInterval);
                timerElement.textContent = 'Time\'s Up!';
                nextQuestion();
            } else {
                timerElement.textContent = `Time Remaining: ${timeRemaining}s`;
            }
        }

        // Function to check the answer
        function checkAnswer(selectedIndex) {
            const questionObj = selectedQuestions[currentQuestionIndex];
            const nextButton = document.getElementById('nextButton');

            // Check if the selected answer is correct
            if (selectedIndex === questionObj.correct) {
                score++;
                document.getElementById('score').textContent = "Score: " + score;
            }

            // Show next button
            nextButton.style.display = 'block';
        }

        // Function to go to the next question
        function nextQuestion() {
            currentQuestionIndex++;
            if (currentQuestionIndex < selectedQuestions.length) {
                loadQuestion();
            } else {
                showFinalScore();
            }
        }

        // Function to show final score
        function showFinalScore() {
            const questionContainer = document.querySelector('.quiz-container');
            questionContainer.innerHTML = `<h2>Game Over</h2><p>Your final score is: ${score}/${selectedQuestions.length}</p>`;
            document.getElementById('restartButton').style.display = 'block'; // Show restart button
        }

        // Function to restart the quiz
        function restartQuiz() {
            currentQuestionIndex = 0;
            score = 0;
            document.getElementById('score').textContent = "Score: 0";
            document.getElementById('restartButton').style.display = 'none';
            shuffleQuestions();
            loadQuestion();
        }

        // Start the quiz
        shuffleQuestions();
        loadQuestion();
    </script>
</body>
</html>
