<!-- are-you-ready.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Are You Ready?</title>
    <style>
        /* Basic styles for the "Are You Ready?" screen */
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .ready-container {
            text-align: center;
        }
        .start-button {
            padding: 10px 20px;
            font-size: 1.5em;
            background-color: orange;
            color: white;
            border: none;
            cursor: pointer;
        }
        .start-button:hover {
            background-color: darkorange;
        }
    </style>
</head>
<body>
    <div class="ready-container">
        <h1>Are You Ready?</h1>
        <p>Click the button below to start the game.</p>
        <button class="start-button" onclick="window.location.href='Lingo_Game.php'">Start Game</button>
    </div>
</body>
</html>
