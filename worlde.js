(function () {
    const WORDS = ["APPLE", "BRAVE", "CRANE", "DREAM", "EAGLE", "FAITH", "GHOST", "HONEY", "IDEAL", "JOKER"];
    const MAX_GUESSES = 6;
    const WORD_LENGTH = 5;

    let answer = WORDS[Math.floor(Math.random() * WORDS.length)];
    let currentGuess = 0;
    let isGameOver = false;

    const grid = document.getElementById('wordle-grid');
    const input = document.getElementById('wordle-input');
    const submitBtn = document.getElementById('wordle-submit');
    const message = document.getElementById('wordle-message');

    function setMessage(text, isError = false) {
        message.textContent = text;
        message.style.color = isError ? 'red' : 'green';
    }

    function clearMessage() {
        message.textContent = '';
    }

    function checkGuess(guess) {
        guess = guess.toUpperCase();
        if (guess.length !== WORD_LENGTH) {
            setMessage("Guess must be 5 letters.", true);
            return false;
        }
        return true;
    }

    function updateGrid(guess) {
        const startIndex = currentGuess * WORD_LENGTH;
        const answerLetters = answer.split('');
        const guessLetters = guess.split('');
        const cellElements = grid.children;

        // First pass: mark correct letters
        let letterStatus = Array(WORD_LENGTH).fill('absent');
        let answerLetterCount = {};

        // Count letters in answer
        answerLetters.forEach(letter => {
            answerLetterCount[letter] = (answerLetterCount[letter] || 0) + 1;
        });

        // Mark correct letters
        for (let i = 0; i < WORD_LENGTH; i++) {
            if (guessLetters[i] === answerLetters[i]) {
                letterStatus[i] = 'correct';
                answerLetterCount[guessLetters[i]]--;
            }
        }

        // Mark present letters
        for (let i = 0; i < WORD_LENGTH; i++) {
            if (letterStatus[i] === 'correct') continue;
            if (answerLetterCount[guessLetters[i]] > 0) {
                letterStatus[i] = 'present';
                answerLetterCount[guessLetters[i]]--;
            }
        }

        // Update cells
        for (let i = 0; i < WORD_LENGTH; i++) {
            const cell = cellElements[startIndex + i];
            cell.textContent = guessLetters[i];
            cell.classList.add(letterStatus[i]);
        }
    }

    function endGame(win) {
        isGameOver = true;
        if (win) {
            setMessage("Congratulations! You guessed the word!");
        } else {
            setMessage("Game over! The word was: " + answer, true);
        }
        input.disabled = true;
        submitBtn.disabled = true;
    }

    submitBtn.addEventListener('click', () => {
        if (isGameOver) return;
        const guess = input.value.trim().toUpperCase();
        if (!checkGuess(guess)) return;
        updateGrid(guess);
        currentGuess++;
        if (guess === answer) {
            endGame(true);
        } else if (currentGuess >= MAX_GUESSES) {
            endGame(false);
        } else {
            clearMessage();
        }
        input.value = '';
        input.focus();
    });

    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            submitBtn.click();
        }
    });
})();
