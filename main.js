//Initialize canvas and context
const canvas = document.getElementById('gameCanvas');
const context = canvas.getContext('2d');

//Cell size and initialize dimensions
const cellSize = 10;
let numRows = Math.floor(canvas.height / cellSize);
let numCols = Math.floor(canvas.width / cellSize);

//Animation speed
const animationSpeed = 250; //milliseconds

let generationCount = 0;


//Calculate grid dimensions
function calculateDimensions() {
    numRows = Math.floor(canvas.height / cellSize);
    numCols = Math.floor(canvas.width / cellSize);
}

calculateDimensions();

//Create the grid with random cells
function createGrid(){
    const grid = [];
    for(let i = 0; i < numRows; i++){
        grid[i] = [];
        for(let j = 0; j < numCols; j++){
            grid[i][j] = Math.random() > 0.7 ? 1 : 0;
        }
    }
    return grid;
}

let grid = createGrid();
let isRunning = false;
let animationId = null;

//Draw the grid with grid lines
function drawGrid(){
    context.clearRect(0, 0, canvas.width, canvas.height);
    //Cells
    for(let i = 0; i < numRows; i++){
        for(let j = 0; j < numCols; j++){
            if(grid[i][j] === 1){
                context.fillStyle = 'black';
                context.fillRect(j * cellSize, i * cellSize, cellSize, cellSize);
            }
        }
    }

    //Grid lines
    context.strokeStyle = '#ccc';
    context.lineWidth = 0.5;
    for (let i = 0; i <= numRows; i++) {
        context.beginPath();
        context.moveTo(0, i * cellSize);
        context.lineTo(canvas.width, i * cellSize);
        context.stroke();
    }
    for (let i = 0; i <= numCols; i++) {
        context.beginPath();
        context.moveTo(j * cellSize, 0);
        context.lineTo(j * cellSize, canvas.height);
        context.stroke();
    }
}

//Update the grid
function updateGrid(){
    const newGrid = [];
    for(let i = 0; i < numRows; i++){
        newGrid[i] = [];
        for(let j = 0; j < numCols; j++){
            const neighbors = countNeighbors(i, j);
            //Neighbor rules
            if(grid[i][j] === 1 && (neighbors < 2 || neighbors > 3)){
                newGrid[i][j] = 0; 
            }else if(grid[i][j] === 0 && neighbors === 3){
                newGrid[i][j] = 1; 
            }else{
                newGrid[i][j] = grid[i][j]; 
            }
        }
    }
    grid = newGrid;
    //Update generation
    generationCount++;
    document.getElementById('generationCount').textContent = generationCount;
}

//Count live neighbors
function countNeighbors(row, col){
    let count = 0;
    for(let i = -1; i <= 1; i++){
        for(let j = -1; j <= 1; j++){
            const neighborR = row + i;
            const neighborC = col + j;
            //Edge cases
            if(neighborR >= 0 && neighborR < numRows && neighborC >= 0 && neighborC < numCols && !(i === 0 && j === 0)){
                count += grid[neighborR][neighborC];
            }
        }
    }
    return count;
}

//Main loop for animation
function mainLoop(){
    updateGrid();
    drawGrid();
    if(isRunning){
        setTimeout(() => {
            animationId = requestAnimationFrame(mainLoop);
        }, animationSpeed);
    }
}

//Click to toggle cells
canvas.addEventListener('click', function (event) {
    if (isRunning) return; //Don't allow toggling while running

    const rect = canvas.getBoundingClientRect();
    const x = event.clientX - rect.left;
    const y = event.clientY - rect.top;

    const col = Math.floor(x / cellSize);
    const row = Math.floor(y / cellSize);

    //Toggle cell
    if(row >= 0 && row < numRows && col >= 0 && col < numCols){
        grid[row][col] = grid[row][col] ? 0 : 1;
        drawGrid();
    }
});

// Button events -- Start, Stop, Restart, Plus One, Plus Many, Pattern Selection

//Start
document.getElementById('startButton').addEventListener('click', function () {
    if(!isRunning){
        isRunning = true;
        mainLoop();
    }
});

//Stop
document.getElementById('stopButton').addEventListener('click', function () {
    isRunning = false;
    cancelAnimationFrame(animationId);
});

//Restart
document.getElementById('restartButton').addEventListener('click', function () {
    isRunning = false;
    cancelAnimationFrame(animationId);
    grid = createGrid();
    drawGrid();
    generationCount = 0;
    document.getElementById('generationCount').textContent = generationCount;
});

//Plus one generation
document.getElementById('plusOneButton').addEventListener('click', function () {
    if(!isRunning){
        updateGrid();
        drawGrid();
    }
});

//Plus 23 generations
document.getElementById('plusManyButton').addEventListener('click', function () {
    let generations = 0;
    const maxGenerations = 23;

    function animateStep(){
        if(generations < maxGenerations){
            updateGrid();
            drawGrid();
            generations++;
            setTimeout(animateStep, animationSpeed);
        }
    }

    if (!isRunning) animateStep();
});

//Insert pattern
document.getElementById('patternButton').addEventListener('change', function () {
    const selectedPattern = this.value;
    isRunning = false;
    cancelAnimationFrame(animationId);
    insertPattern(selectedPattern);
});

//Resize canvas
document.getElementById('resizeCanvasButton').addEventListener('click', function () {
    generationCount = 0;
    document.getElementById('generationCount').textContent = generationCount;

    //Multiply by 10 to get pixel size to match cell size
    const newWidth = parseInt(document.getElementById('canvasWidth').value) * 10;
    const newHeight = parseInt(document.getElementById('canvasHeight').value) * 10;

    if (!isNaN(newWidth) && !isNaN(newHeight)) {
        const oldGrid = grid;
        const oldRows = numRows;
        const oldCols = numCols;

        canvas.width = newWidth;
        canvas.height = newHeight;

        calculateDimensions();
        const newGrid = createBlankGrid();

        const rowOffset = Math.floor((numRows - oldRows) / 2);
        const colOffset = Math.floor((numCols - oldCols) / 2);

        for (let i = 0; i < oldRows; i++) {
            for (let j = 0; j < oldCols; j++) {
                const newRow = i + rowOffset;
                const newCol = j + colOffset;
                if (newRow >= 0 && newRow < numRows && newCol >= 0 && newCol < numCols) {
                    newGrid[newRow][newCol] = oldGrid[i][j];
                }
            }
        }

        isRunning = false;
        cancelAnimationFrame(animationId);
        grid = newGrid;
        drawGrid();
    }
});

//Create an empty grid with all dead cells
function createBlankGrid(){
    const grid = [];
    for(let i = 0; i < numRows; i++){
        grid[i] = new Array(numCols).fill(0);
    }
    return grid;
}

//Insert a pattern
function insertPattern(patternName){
    grid = createBlankGrid();

    generationCount = 0;
    document.getElementById('generationCount').textContent = generationCount;

    let pattern = [];

    switch(patternName){
        case 'beehive':
            pattern = [
                [0, 1], [0, 2],
                [1, 0], [1, 3],
                [2, 1], [2, 2]
            ];
            break;
        case 'blinker':
            pattern = [
                [0, 1], [1, 1], [2, 1]
            ];
            break;
        case 'beacon':
            pattern = [
                [0, 0], [0, 1], [1, 0], [1, 1],
                [2, 2], [2, 3], [3, 2], [3, 3]
            ];
            break;

        case 'glider':
            pattern = [
                [0, 1],
                [1, 2],
                [2, 0], [2, 1], [2, 2]
            ];
            break;
    }

    const offsetRow = Math.floor(numRows / 2) - 2;
    const offsetCol = Math.floor(numCols / 2) - 2;

    for(const [r, c] of pattern){
        const row = offsetRow + r;
        const col = offsetCol + c;
        if(row >= 0 && row < numRows && col >= 0 && col < numCols){
            grid[row][col] = 1;
        }
    }

    drawGrid();
}