const board = [
    [5, 3, '', '', 7, '', '', '', ''],
    [6, '', '', 1, 9, 5, '', '', ''],
    ['', 9, 8, '', '', '', '', 6, ''],
    [8, '', '', '', 6, '', '', '', 3],
    [4, '', '', 8, '', 3, '', '', 1],
    [7, '', '', '', 2, '', '', '', 6],
    ['', 6, '', '', '', '', 2, 8, ''],
    ['', '', '', 4, 1, 9, '', '', 5],
    ['', '', '', '', 8, '', '', 7, 9],
];

const solvedBoard = [
    [5, 3, 4, 6, 7, 8, 9, 1, 2],
    [6, 7, 2, 1, 9, 5, 3, 4, 8],
    [1, 9, 8, 3, 4, 2, 5, 6, 7],
    [8, 5, 9, 7, 6, 1, 4, 2, 3],
    [4, 2, 6, 8, 5, 3, 7, 9, 1],
    [7, 1, 3, 9, 2, 4, 8, 5, 6],
    [9, 6, 1, 5, 3, 7, 2, 8, 4],
    [2, 8, 7, 4, 1, 9, 6, 3, 5],
    [3, 4, 5, 2, 8, 6, 1, 7, 9],
];

function createBoard() {
    const table = document.getElementById('sudoku-board');
    for (let i = 0; i < 9; i++) {
        const row = document.createElement('tr');
        for (let j = 0; j < 9; j++) {
            const cell = document.createElement('td');
            if (i % 3 === 0) cell.classList.add('thick-border-top');
            if (j % 3 === 0) cell.classList.add('thick-border-left');
            if (j === 8) cell.classList.add('thick-border-right');
            if (i === 8) cell.classList.add('thick-border-bottom');

            const input = document.createElement('input');
            input.setAttribute('type', 'text');
            input.setAttribute('maxlength', '1');
            if (board[i][j] !== '') {
                input.value = board[i][j];
                input.disabled = true;
                input.style.backgroundColor = '#e0e0e0';
            }
            cell.appendChild(input);
            row.appendChild(cell);
        }
        table.appendChild(row);
    }
}

function fillSolution() {
    const table = document.getElementById('sudoku-board');
    const inputs = table.querySelectorAll('input');
    for (let i = 0; i < 9; i++) {
        for (let j = 0; j < 9; j++) {
            const index = i * 9 + j;
            const input = inputs[index];
            input.value = solvedBoard[i][j];
            console.log(`Filling cell (${i}, ${j}) with value: ${solvedBoard[i][j]}`);

            input.disabled = true;
            input.style.backgroundColor = '#e0e0e0';
        }
    }
}

function checkSolution() {

    const inputs = document.querySelectorAll('#sudoku-board input');
    const grid = Array.from({ length: 9 }, () => Array(9).fill(0));

    for (let i = 0; i < inputs.length; i++) {
        const input = inputs[i];
        const row = Math.floor(i / 9);
        const col = i % 9;
        const val = input.value.trim();

        if (val === '') {
            document.getElementById('result').textContent = '⚠ Please fill all cells before checking.';
            return;
        }

        grid[row][col] = parseInt(val) || 0;
    }

    if (isValidSudoku(grid)) {
        document.getElementById('result').textContent = '✔ Correct solution!';
    } else {
        document.getElementById('result').textContent = '✖ Incorrect, try again.';
    }
}

function isValidSudoku(grid) {
    const isValid = arr => arr.filter(v => v).length === new Set(arr.filter(v => v)).size;
    for (let i = 0; i < 9; i++) {
        const row = grid[i];
        const col = grid.map(row => row[i]);
        const box = [];
        const rowStart = Math.floor(i / 3) * 3;
        const colStart = (i % 3) * 3;
        for (let r = 0; r < 3; r++)
            for (let c = 0; c < 3; c++)
                box.push(grid[rowStart + r][colStart + c]);

        if (!isValid(row) || !isValid(col) || !isValid(box)) return false;
    }
    return true;
}

function resetBoard() {
    const table = document.getElementById('sudoku-board');
    const inputs = table.querySelectorAll('input');
    for (let i = 0; i < 9; i++) {
        for (let j = 0; j < 9; j++) {
            const index = i * 9 + j;
            const input = inputs[index];
            if (board[i][j] !== '') {
                input.value = board[i][j];
                input.disabled = true;
                input.style.backgroundColor = '#e0e0e0';
            } else {
                input.value = '';
                input.disabled = false;
                input.style.backgroundColor = '';
            }
        }
    }
    document.getElementById('result').textContent = '';
}

createBoard();
