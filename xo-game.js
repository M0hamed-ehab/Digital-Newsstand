document.addEventListener('DOMContentLoaded', () => {
    const cells = document.querySelectorAll('#XO td[data-cell]');
    const board = Array(9).fill(null);
    const player = 'X';
    const computer = 'O';
    let gameActive = true;

    const winningCombinations = [
        [0, 1, 2],
        [3, 4, 5],
        [6, 7, 8],
        [0, 3, 6],
        [1, 4, 7],
        [2, 5, 8],
        [0, 4, 8],
        [2, 4, 6]
    ];

    function checkWinner(board, symbol) {
        return winningCombinations.some(combination => {
            return combination.every(index => board[index] === symbol);
        });
    }

    function isDraw(board) {
        return board.every(cell => cell !== null);
    }

    function computerMove() {
        if (!gameActive) return;

        // Simple AI: pick random empty cell
        const emptyIndices = board.map((val, idx) => val === null ? idx : null).filter(val => val !== null);
        if (emptyIndices.length === 0) return;

        // For better AI, try to win or block player
        // Check if computer can win
        for (let idx of emptyIndices) {
            board[idx] = computer;
            if (checkWinner(board, computer)) {
                updateCell(idx, computer);
                gameActive = false;
                announceWinner(computer);
                return;
            }
            board[idx] = null;
        }
        // Check if player can win next move, block it
        for (let idx of emptyIndices) {
            board[idx] = player;
            if (checkWinner(board, player)) {
                board[idx] = computer;
                updateCell(idx, computer);
                if (checkWinner(board, computer)) {
                    gameActive = false;
                    announceWinner(computer);
                }
                return;
            }
            board[idx] = null;
        }
        // Otherwise pick random
        const randomIndex = emptyIndices[Math.floor(Math.random() * emptyIndices.length)];
        board[randomIndex] = computer;
        updateCell(randomIndex, computer);

        if (checkWinner(board, computer)) {
            gameActive = false;
            announceWinner(computer);
            return;
        }
        if (isDraw(board)) {
            gameActive = false;
            announceDraw();
        }
    }

    function updateCell(index, symbol) {
        cells[index].textContent = symbol;
    }

    function announceWinner(winner) {
        const caption = document.querySelector('#XO caption #result');
        caption.textContent = ` ${winner}`;
    }

    function announceDraw() {
        const caption = document.querySelector('#XO caption #result');
        caption.textContent = " It's a draw!";
    }

    cells.forEach((cell, index) => {
        cell.addEventListener('click', () => {
            if (!gameActive) return;
            if (board[index] !== null) return;

            board[index] = player;
            updateCell(index, player);

            if (checkWinner(board, player)) {
                gameActive = false;
                announceWinner(player);
                return;
            }
            if (isDraw(board)) {
                gameActive = false;
                announceDraw();
                return;
            }
            // Computer move after slight delay
            setTimeout(computerMove, 300);
        });
    });
});
