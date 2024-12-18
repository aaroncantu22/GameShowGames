let targetWord = "<?php echo $rand; ?>";
let wordLength = targetWord.length;
let firstLetter = "<?php echo $firstLetter; ?>";
let secondLetter = "<?php echo $secondLetter; ?>";
let attempts = 0;

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

function checkGuess() {
    const guess = document.getElementById("guess").value.toUpperCase();
    const resultContainer = document.getElementById("resultContainer");

    if (guess.length === wordLength) { // Check for correct length
        attempts++;
        let resultHtml = '';
        
        // Display each letter in the guessed word with appropriate styling
        for (let i = 0; i < guess.length; i++) {
            if (guess[i] === targetWord[i]) {
                resultHtml += `<span class="square correct">${guess[i]}</span>`;
            } else if (targetWord.includes(guess[i])) {
                resultHtml += `<span class="square wrongplace">${guess[i]}</span>`;
            } else {
                resultHtml += `<span class="square default">${guess[i]}</span>`;
            }
        }

        // Display the result row in the resultContainer
        resultContainer.innerHTML += resultHtml + '<br>';
        document.getElementById("guess").value = '';

        // If guessed correctly, display a message
        if (guess === targetWord) {
            resultContainer.innerHTML += `<p style="color: green; font-size: 2em;">Congratulations! You guessed the word correctly!</p>`;
        } else if (attempts >= 6) {
            resultContainer.innerHTML += `<p style="color: red; font-size: 2em;">Game Over! The word was ${targetWord}</p>`;
        }

    } else {
        alert(`Please enter a ${wordLength}-letter word.`);
    }
}
