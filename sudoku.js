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

function checkSolution() {
    const inputs = document.querySelectorAll('input');
    const grid = Array.from({ length: 9 }, () => Array(9).fill(0));
    inputs.forEach((input, index) => {
        const row = Math.floor(index / 9);
        const col = index % 9;
        grid[row][col] = parseInt(input.value) || 0;
    });

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

createBoard();
