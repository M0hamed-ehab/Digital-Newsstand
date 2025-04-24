document.addEventListener('DOMContentLoaded', () => {
    const gameContainer = document.querySelector('.game-container');

    // Configuration
    const rows = 9;
    const cols = 9;
    const minesCount = 10;

    let board = [];
    let minePositions = new Set();
    let revealedCount = 0;
    let flagsCount = 0;
    let gameOver = false;

    // Message display element
    let messageElement;

    // Create board UI
    function createBoard() {
        const boardElement = document.createElement('div');
        boardElement.id = 'minesweeper-board';
        boardElement.style.display = 'grid';
        boardElement.style.gridTemplateRows = `repeat(${rows}, 50px)`;
        boardElement.style.gridTemplateColumns = `repeat(${cols}, 50px)`;
        boardElement.style.gap = '2px';
        boardElement.style.margin = '20px auto';
        boardElement.style.width = `${cols * 32}px`;
        boardElement.style.userSelect = 'none';

        for (let r = 0; r < rows; r++) {
            board[r] = [];
            for (let c = 0; c < cols; c++) {
                const cell = document.createElement('div');
                cell.classList.add('cell');
                cell.dataset.row = r;
                cell.dataset.col = c;
                cell.style.width = '50px';
                cell.style.height = '50px';
                cell.style.backgroundColor = '#ccc';
                cell.style.border = '1px solid #999';
                cell.style.display = 'flex';
                cell.style.alignItems = 'center';
                cell.style.justifyContent = 'center';
                cell.style.fontWeight = 'bold';
                cell.style.fontSize = '18px';
                cell.style.cursor = 'pointer';
                cell.style.userSelect = 'none';

                cell.addEventListener('click', onCellClick);
                cell.addEventListener('contextmenu', onCellRightClick);

                boardElement.appendChild(cell);
                board[r][c] = {
                    element: cell,
                    isMine: false,
                    isRevealed: false,
                    isFlagged: false,
                    adjacentMines: 0
                };
            }
        }
        return boardElement;
    }

    // Place mines randomly
    function placeMines() {
        minePositions.clear();
        while (minePositions.size < minesCount) {
            const pos = Math.floor(Math.random() * rows * cols);
            minePositions.add(pos);
        }
        minePositions.forEach(pos => {
            const r = Math.floor(pos / cols);
            const c = pos % cols;
            board[r][c].isMine = true;
        });
    }

    // Calculate adjacent mines for each cell
    function calculateAdjacentMines() {
        for (let r = 0; r < rows; r++) {
            for (let c = 0; c < cols; c++) {
                if (board[r][c].isMine) {
                    board[r][c].adjacentMines = -1;
                    continue;
                }
                let count = 0;
                for (let dr = -1; dr <= 1; dr++) {
                    for (let dc = -1; dc <= 1; dc++) {
                        if (dr === 0 && dc === 0) continue;
                        const nr = r + dr;
                        const nc = c + dc;
                        if (nr >= 0 && nr < rows && nc >= 0 && nc < cols) {
                            if (board[nr][nc].isMine) count++;
                        }
                    }
                }
                board[r][c].adjacentMines = count;
            }
        }
    }

    // Reveal cell
    function revealCell(r, c) {
        const cell = board[r][c];
        if (cell.isRevealed || cell.isFlagged) return;
        cell.isRevealed = true;
        revealedCount++;
        cell.element.style.backgroundColor = '#e0e0e0';
        cell.element.style.cursor = 'default';

        if (cell.isMine) {
            cell.element.textContent = '💣';
            cell.element.style.color = 'red';
            gameOver = true;
            revealAllMines();
            setMessage('Game Over! You clicked on a mine.');
            return;
        }

        if (cell.adjacentMines > 0) {
            cell.element.textContent = cell.adjacentMines;
            cell.element.style.color = getNumberColor(cell.adjacentMines);
        } else {
            // Reveal neighbors recursively
            for (let dr = -1; dr <= 1; dr++) {
                for (let dc = -1; dc <= 1; dc++) {
                    if (dr === 0 && dc === 0) continue;
                    const nr = r + dr;
                    const nc = c + dc;
                    if (nr >= 0 && nr < rows && nc >= 0 && nc < cols) {
                        revealCell(nr, nc);
                    }
                }
            }
        }

        checkWin();
    }

    // Reveal all mines when game over
    function revealAllMines() {
        for (let pos of minePositions) {
            const r = Math.floor(pos / cols);
            const c = pos % cols;
            const cell = board[r][c];
            if (!cell.isRevealed) {
                cell.element.textContent = '💣';
                cell.element.style.backgroundColor = '#f8d7da';
                cell.element.style.color = 'red';
            }
        }
    }

    // Handle left click
    function onCellClick(e) {
        if (gameOver) return;
        const r = parseInt(this.dataset.row);
        const c = parseInt(this.dataset.col);
        revealCell(r, c);
    }

    // Handle right click (flag)
    function onCellRightClick(e) {
        e.preventDefault();
        if (gameOver) return;
        const r = parseInt(this.dataset.row);
        const c = parseInt(this.dataset.col);
        const cell = board[r][c];
        if (cell.isRevealed) return;

        if (cell.isFlagged) {
            cell.isFlagged = false;
            cell.element.textContent = '';
            flagsCount--;
        } else {
            if (flagsCount < minesCount) {
                cell.isFlagged = true;
                cell.element.textContent = '🚩';
                cell.element.style.color = 'red';
                flagsCount++;
            }
        }
    }

    // Check if player won
    function checkWin() {
        if (revealedCount === rows * cols - minesCount) {
            gameOver = true;
            setMessage('Congratulations! You cleared the minefield!');
        }
    }

    // Get color for numbers
    function getNumberColor(num) {
        const colors = {
            1: 'blue',
            2: 'green',
            3: 'red',
            4: 'darkblue',
            5: 'darkred',
            6: 'turquoise',
            7: 'black',
            8: 'gray'
        };
        return colors[num] || 'black';
    }

    // Set message text
    function setMessage(text) {
        if (messageElement) {
            messageElement.textContent = text;
        }
    }

    // Reset game
    function resetGame() {
        revealedCount = 0;
        flagsCount = 0;
        gameOver = false;
        minePositions.clear();
        board.forEach(row => {
            row.forEach(cell => {
                cell.isMine = false;
                cell.isRevealed = false;
                cell.isFlagged = false;
                cell.adjacentMines = 0;
                cell.element.textContent = '';
                cell.element.style.backgroundColor = '#ccc';
                cell.element.style.color = 'black';
                cell.element.style.cursor = 'pointer';
            });
        });
        setMessage('');
        placeMines();
        calculateAdjacentMines();
    }

    // Create reset button
    function createResetButton() {
        const btn = document.createElement('button');
        btn.textContent = 'Restart Game';
        btn.style.margin = '10px auto';
        btn.style.display = 'block';
        btn.style.padding = '8px 16px';
        btn.style.fontSize = '16px';
        btn.style.cursor = 'pointer';
        btn.addEventListener('click', () => {
            resetGame();
        });
        return btn;
    }

    // Initialize game
    function init() {
        gameContainer.innerHTML = '';

        // Create message element
        messageElement = document.createElement('div');
        messageElement.id = 'game-message';
        messageElement.style.textAlign = 'center';
        messageElement.style.fontWeight = 'bold';
        messageElement.style.fontSize = '18px';
        messageElement.style.marginBottom = '10px';
        messageElement.style.height = '24px'; // reserve space
        gameContainer.appendChild(messageElement);

        const boardElement = createBoard();
        gameContainer.appendChild(boardElement);
        const resetBtn = createResetButton();
        gameContainer.appendChild(resetBtn);
        placeMines();
        calculateAdjacentMines();
    }

    init();
});
