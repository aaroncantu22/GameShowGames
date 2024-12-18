<?php
// PHP code remains the same
$quicklist = ["Gravy", "Gobble", "Pumpkin", "Carve", "Leaves", "Autumn", "Patch",
    "Feast", "Family", "Pecan", "Potato", "Apple", "Harvest",
    "Dessert", "Wheat", "Thanks", "Maize", "Butter", "Mashed", "Platter",
    "Feather", "Recipe", "Napping", "Bloat", "Gourd", 
    "Baking", "Coffee","Pilgrim"];

// Track all words and shuffle them for random selection
$wordsArray = $quicklist;
shuffle($wordsArray);
$rand = strtoupper($wordsArray[array_rand($wordsArray)]);  // Ensure it's uppercase
$firstLetter = $rand[0];
$randomIndex = rand(1, strlen($rand) - 1);
$secondLetter = $rand[$randomIndex];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lingo Game</title>
    <style>
   
        /* CSS code remains the same */
        * { box-sizing: border-box; }
        button {
            margin: auto;
            background: white;
            border: 1px solid black;
            padding: 10px 15px;
            font-size: 20px;
        }

        .centered-checkbox-container {
            text-align: center;
            margin-top: 15px;
        }

        .centered-checkbox {
            display: inline-block;
            margin: 0 auto;
        }
        button:hover { background-color: green; color: white; }
        body { background: white; }
        #msgBox { text-align: center; font-size: 3.5em; font-family: 'Comic Sans MS', sans-serif; margin: 0.1em 0em; }
        #smallMsg { text-align: center; padding: 0px 0px 10px 0px; font-size: 1.1em; }
        #guess { border: 1px black solid; padding: 5px; margin: 10px auto; display: block; }
        #container { margin: auto; width: 100em; text-align: center; }
        .correct { background: green; }
        .wrongplace { background: yellow; }
        .default { background: #ffff99; }
        .square { vertical-align: top; margin-top: 4px; border: 1px black solid; border-radius: 5px; display: inline-block; height: 1.25em; width: 1.25em; text-align: center; font-size: 3.5em; font-family: 'Lato', sans-serif; }
    </style>
</head>
<body>
    <div id="container">
        <h1 id="msgBox">Lingo Game</h1>
        <p id="smallMsg">Guess as many words as you can in 2 minutes!</p>

        <!-- Display Timer -->
        <div id="timer" style="text-align: center; font-size: 1.5em; color: red;">Time Left: 2:00</div>

        <!-- Display Score -->
        <div id="score" style="text-align: center; font-size: 1.5em;">Score: 0</div>

        <!-- Display Hint -->
        <div id="hintContainer">
            <p>Hint:</p>
            <div id="hint" style="display: flex; justify-content: center;"></div>
        </div>

        <!-- Guessing Input Field -->
        <div class="centered-checkbox-container">
            <div id="guessContainer">
                <input type="text" id="guess" maxlength="9" placeholder="Enter guess">
                <button id="checkButton" onclick="checkGuess()">Check</button>
            </div>
        </div>

        <!-- Bonus Letter Button -->
        <div class="centered-checkbox-container">
            <button id="bonusButton" onclick="useBonusLetter()">Use Bonus Letter (3 Left)</button>
        </div>

        <!-- Result Area -->
        <div id="resultContainer"></div>

        <!-- Next Puzzle Button -->
        <div id="nextPuzzleContainer" style="text-align: center; margin-top: 20px;"></div>
        <button class="start-button" onclick="window.location.href='are_you_ready.php'">New Game</button>

        <script>
            let wordsArray = <?php echo json_encode($wordsArray); ?>;
            let targetWord = "<?php echo $rand; ?>";
            let wordLength = targetWord.length;
            let firstLetter = "<?php echo $firstLetter; ?>";
            let secondLetter = "<?php echo $secondLetter; ?>";
            let randomIndex = <?php echo $randomIndex; ?>;
            let attempts = 1;
            let bonusCount = 3; // Total bonus letters for session
            let score = 0; // Total correct guesses
            let guessedLetters = new Set(); // Track guessed letters
            let timeRemaining = 120; // 2 minutes in seconds

            // Display initial hint with the first letter and a random second letter
            let hintArray = Array(wordLength).fill("_");
            hintArray[0] = firstLetter;
            hintArray[randomIndex] = secondLetter;

            // Render hint as boxes in hintContainer
            const hintContainer = document.getElementById("hint");
            hintArray.forEach(letter => {
                const span = document.createElement("span");
                span.classList.add("square");
                span.textContent = letter;
                hintContainer.appendChild(span);
            });

            function updateHintDisplay() {
                hintContainer.innerHTML = "";
                hintArray.forEach(letter => {
                    const span = document.createElement("span");
                    span.classList.add("square");
                    span.textContent = letter;
                    hintContainer.appendChild(span);
                });
            }

            let hasFinished = false;
            function startGame() {
                document.getElementById("readyScreen").style.display = "none"; // Hide ready screen
                document.getElementById("container").style.display = "block"; // Show game container
                hasFinished = false;
                startTimer(); // Start the timer
            }

            /*function startTimer() {
                const timerElement = document.getElementById("timer");
                Console.log("Running");
                const timer = setInterval(() => {
                    if (timeRemaining == 0 && !hasFinished) {
                        //clearInterval(timer);
                        document.getElementById("checkButton").disabled = true;
                        document.getElementById("bonusButton").disabled = true;
                        document.getElementById("resultContainer").innerHTML = `<p style="color: blue; font-size: 2em;">Game Over! Your score: ${score}</p>`;
                        hasFinished = true;

                    } else {
                        timeRemaining--;
                        let minutes = Math.floor(timeRemaining / 60);
                        let seconds = timeRemaining % 60;
                        timerElement.textContent = `Time Left: ${minutes}:${seconds < 10 ? "0" : ""}${seconds}`;
                    }
                }, 1000);
            }*/

            function checkGuess() {
            const guess = document.getElementById("guess").value.toUpperCase(); // Ensure guess is uppercase
            const resultContainer = document.getElementById("resultContainer");
            const nextPuzzleContainer = document.getElementById("nextPuzzleContainer");

            if (guess.length === wordLength) {
                attempts++;
                let resultHtml = '';
                const matchedIndices = Array(wordLength).fill(false); // Track matched indices for targetWord

                // First, mark correct positions
                for (let i = 0; i < guess.length; i++) {
                    if (guess[i] === targetWord[i]) {
                        resultHtml += `<span class="square correct">${guess[i]}</span>`;
                        matchedIndices[i] = true; // Mark this index as matched
                    } else {
                        resultHtml += `<span class="square default">${guess[i]}</span>`;
                    }
                }

                // Update guessed letters set with the current guess
                for (let letter of guess) {
                    guessedLetters.add(letter);
                }

                // Rebuild resultHtml to replace default style with wrongplace style where applicable
                let correctedResultHtml = '';
                for (let i = 0; i < guess.length; i++) {
                    if (resultHtml.includes(`class="square default">${guess[i]}</span>`)) {
                        if (targetWord.includes(guess[i]) && !matchedIndices[i]) {
                            correctedResultHtml += `<span class="square wrongplace">${guess[i]}</span>`;
                        } else {
                            correctedResultHtml += `<span class="square default">${guess[i]}</span>`;
                        }
                    } else {
                        correctedResultHtml += `<span class="square correct">${guess[i]}</span>`;
                    }
                }

                // Update result container and reset input
                resultContainer.innerHTML += correctedResultHtml + '<br>';
                document.getElementById("guess").value = '';

                if (guess === targetWord) {
                    score++;
                    document.getElementById("score").textContent = `Score: ${score}`;
                    resultContainer.innerHTML += `<p style="color: green; font-size: 2em;">Congratulations! You guessed the word correctly!</p>`;
                    nextPuzzleContainer.innerHTML = '<button onclick="loadNewPuzzle()">Next Puzzle</button>';
                } else if (attempts >= 6) {
                    resultContainer.innerHTML += `<p style="color: red; font-size: 2em;">Sorry! The word was ${targetWord}</p>`;
                    nextPuzzleContainer.innerHTML = '<button onclick="loadNewPuzzle()">Next Puzzle</button>';
                }

            } else {
                alert(`Please enter a ${wordLength}-letter word.`);
            }
        }


    function loadNewPuzzle() {
                // Ensure the current puzzle is removed from the wordsArray
                wordsArray = wordsArray.filter(word => word !== targetWord);

                // Check if there are any remaining puzzles
                if (wordsArray.length === 0) {
                    alert("Game Over! You've completed all the puzzles!");
                    return;
                }

                // Pick a new puzzle and make sure it's in uppercase
                targetWord = wordsArray[Math.floor(Math.random() * wordsArray.length)].toUpperCase();
                wordLength = targetWord.length;
                guessedLetters.clear();
                hintArray = Array(wordLength).fill("_");
                hintArray[0] = targetWord[0];
                hintArray[randomIndex] = targetWord[randomIndex];
                updateHintDisplay();
                document.getElementById("resultContainer").innerHTML = '';
                document.getElementById("nextPuzzleContainer").innerHTML = '';
                attempts = 1;
            }

            function useBonusLetter() {
                if (bonusCount > 0) {
                    let validIndices = [];
                    for (let i = 1; i < hintArray.length; i++) {
                        if (hintArray[i] === "_" && !guessedLetters.has(targetWord[i])) {
                            validIndices.push(i);
                        }
                    }

                    if (validIndices.length > 0) {
                        const randomIndex = validIndices[Math.floor(Math.random() * validIndices.length)];
                        hintArray[randomIndex] = targetWord[randomIndex];
                        guessedLetters.add(targetWord[randomIndex]);
                        bonusCount--;
                        document.getElementById("bonusButton").textContent = `Use Bonus Letter (${bonusCount} Left)`;
                        updateHintDisplay();
                    }
                }
            }

            // Timer Function
            function startTimer() {
                const timerElement = document.getElementById("timer");
                setInterval(() => {
                    if (timeRemaining == 0 && !hasFinished) {
                        hasFinished = true;
                        clearInterval(timer);
                        document.getElementById("checkButton").disabled = true;
                        document.getElementById("bonusButton").disabled = true;
                        document.getElementById("resultContainer").innerHTML += `<p style="color: blue; font-size: 2em;">Game Over! Your score: ${score}</p>`;
                    } else if(timeRemaining > 0){
                        timeRemaining--;
                        let minutes = Math.floor(timeRemaining / 60);
                        let seconds = timeRemaining % 60;
                        timerElement.textContent = `Time Left: ${minutes}:${seconds < 10 ? "0" : ""}${seconds}`;
                    }
                }, 1000);
            }

            window.onload = function () {
                startTimer();
            };
        </script>
    </div>
</body>
</html>
