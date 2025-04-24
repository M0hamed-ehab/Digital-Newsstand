const boards = [
    [
        [5, 3, '', '', 7, '', '', '', ''],
        [6, '', '', 1, 9, 5, '', '', ''],
        ['', 9, 8, '', '', '', '', 6, ''],
        [8, '', '', '', 6, '', '', '', 3],
        [4, '', '', 8, '', 3, '', '', 1],
        [7, '', '', '', 2, '', '', '', 6],
        ['', 6, '', '', '', '', 2, 8, ''],
        ['', '', '', 4, 1, 9, '', '', 5],
        ['', '', '', '', 8, '', '', 7, 9],
    ],
    [
        ['', '', '', 2, 6, '', 7, '', 1],
        [6, 8, '', '', 7, '', '', 9, ''],
        [1, 9, '', '', '', 4, 5, '', ''],
        [8, 2, '', 1, '', '', '', 4, ''],
        ['', '', 4, 6, '', 2, 9, '', ''],
        ['', 5, '', '', '', 3, '', 2, 8],
        ['', '', 9, 3, '', '', '', 7, 4],
        ['', 4, '', '', 5, '', '', 3, 6],
        [7, '', 3, '', 1, 8, '', '', ''],
    ],

    [
        ['', '', '', '', '', '', 2, '', ''],
        ['', 8, '', '', 7, '', '', 9, ''],
        [6, '', 2, '', '', '', 5, '', ''],
        ['', 7, '', '', 6, '', '', '', ''],
        ['', '', '', 9, '', 1, '', '', ''],
        ['', '', '', '', 2, '', '', 4, ''],
        ['', '', 5, '', '', '', 6, '', 3],
        ['', 9, '', '', 8, '', '', 7, ''],
        ['', '', 6, '', '', '', '', '', ''],
    ],
    [
        [2, '', '', '', 3, '', '', 4, ''],
        ['', 3, '', 6, '', '', '', '', 7],
        ['', '', 9, '', '', 7, 1, '', 8],
        ['', '', 4, '', 7, 2, '', '', ''],
        ['', 2, 5, '', 8, 1, 9, '', ''],
        [1, '', 3, '', '', 6, '', '', 5],
        ['', '', '', '', 2, '', 4, '', ''],
        [4, '', 6, 8, '', '', '', 7, ''],
        [5, '', '', 9, '', '', 3, '', ''],
    ]
];


const solvedBoards = [
    [
        [5, 3, 4, 6, 7, 8, 9, 1, 2],
        [6, 7, 2, 1, 9, 5, 3, 4, 8],
        [1, 9, 8, 3, 4, 2, 5, 6, 7],
        [8, 5, 9, 7, 6, 1, 4, 2, 3],
        [4, 2, 6, 8, 5, 3, 7, 9, 1],
        [7, 1, 3, 9, 2, 4, 8, 5, 6],
        [9, 6, 1, 5, 3, 7, 2, 8, 4],
        [2, 8, 7, 4, 1, 9, 6, 3, 5],
        [3, 4, 5, 2, 8, 6, 1, 7, 9]
    ],
    [
        [4, 3, 5, 2, 6, 9, 7, 8, 1],
        [6, 8, 2, 5, 7, 1, 4, 9, 3],
        [1, 9, 7, 8, 3, 4, 5, 6, 2],
        [8, 2, 6, 1, 9, 5, 3, 4, 7],
        [3, 7, 4, 6, 8, 2, 9, 1, 5],
        [9, 5, 1, 7, 4, 3, 6, 2, 8],
        [5, 1, 9, 3, 2, 6, 8, 7, 4],
        [2, 4, 8, 9, 5, 7, 1, 3, 6],
        [7, 6, 3, 4, 1, 8, 2, 5, 9]
    ],

    [
        [9, 1, 7, 3, 5, 8, 2, 6, 4],
        [5, 8, 4, 2, 7, 6, 3, 9, 1],
        [6, 3, 2, 1, 4, 9, 5, 8, 7],
        [2, 7, 1, 4, 6, 5, 8, 3, 9],
        [4, 6, 8, 9, 3, 1, 7, 5, 2],
        [3, 5, 9, 8, 2, 7, 1, 4, 6],
        [8, 2, 5, 7, 9, 4, 6, 1, 3],
        [1, 9, 3, 6, 8, 2, 4, 7, 5],
        [7, 4, 6, 5, 1, 3, 9, 2, 8]

    ],
    [
        [2, 5, 7, 1, 3, 8, 6, 4, 9],
        [8, 3, 1, 6, 4, 9, 2, 5, 7],
        [6, 4, 9, 2, 5, 7, 1, 3, 8],
        [9, 6, 4, 5, 7, 2, 8, 1, 3],
        [7, 2, 5, 3, 8, 1, 9, 6, 4],
        [1, 8, 3, 4, 9, 6, 7, 2, 5],
        [3, 1, 8, 7, 2, 5, 4, 9, 6],
        [4, 9, 6, 8, 1, 3, 5, 7, 2],
        [5, 7, 2, 9, 6, 4, 3, 8, 1]

    ]
];


let board = boards[Math.floor(Math.random() * boards.length)];
let solvedBoard = solvedBoards[boards.indexOf(board)];

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
            document.getElementById('result').style.color = 'orange';

            return;
        }

        grid[row][col] = parseInt(val) || 0;
    }

    if (isValidSudoku(grid)) {
        document.getElementById('result').textContent = '✔ Correct solution!';
        document.getElementById('result').style.color = 'green';


    } else {
        document.getElementById('result').textContent = '✖ Incorrect, try again.';
        document.getElementById('result').style.color = 'red';
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
