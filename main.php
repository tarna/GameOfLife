<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conway's Game of Life</title>
    <link rel="stylesheet" href="main.css">
    <script defer src="main.js"></script>
</head>

<body>
    <h1>Conway's Game of Life</h1>
    <canvas id="gameCanvas" width="600" height="400"></canvas>
    <br>
    <br>
    <div id="labels">
    <h2>
        <div id="user">User Name: <span id="userName">[USERNAME]</span></div>
        <div id="generation">Generation: <span id="generationCount">0</span></div>
    </h2>   
</div>
    <div id="buttons">
        <button id="startButton">Start</button>
        <button id="stopButton">Stop</button>

        <button id="plusOneButton">+1 Generation</button>
        <button id="plusManyButton">+23 Generation</button>
        Load Pattern: 
        <select name="patternButton" id="patternButton">
            <option value="beehive">Beehive</option>
            <option value="blinker">Blinker</option>
            <option value="beacon">Beacon</option>
            <option value="glider">Glider</option>
        </select>
        <button id="restartButton">Restart</button>

        <br><br>
        <label for="canvasWidth">Grid Width:</label>
        <input type="number" id="canvasWidth" value="60" min="1">

        <label for="canvasHeight">Grid Height:</label>
        <input type="number" id="canvasHeight" value="40" min="1">

        <button id="resizeCanvasButton">Resize Grid</button>

        <?php
            require 'database.php';
            $user = getUser();
            if ($user['role'] === 'admin') {
                echo '<button id="adminButton">Admin Panel</button>';
            }
        ?>
    </div>

</body>

</html>
