<?php
    include 'includes/navigation.php';
    echo'<link rel="stylesheet" href="styles.css">';

    $csvFile = 'games/games.csv';
    $fileOpen = fopen($csvFile, 'r');

    while (($row = fgetcsv($fileOpen)) !== false) {
        //grabs the csv information
        $gameCode = htmlspecialchars($row[0]);
        $gameName = htmlspecialchars($row[1]);
        $gameConsole = htmlspecialchars($row[2]);
        $gamePrice = htmlspecialchars($row[3]);
        $gameImage = htmlspecialchars($row[4]);

        //puts the csv data onto the webpage
        echo '<div id="gamesOutPut">';
        echo("Game Name: " . $gameCode . "<br>");
        echo("Game Code: ". $gameName . "<br>");
        echo("Game Console: " . $gameConsole . "<br>");
        echo("Game Price: $" . $gamePrice . "<br>");
        echo 'Game Image: <br> <img src="' . $gameImage . '" alt="' . $gameName . '" width="100"><br> ';
        echo("<br> </div>");
    }

    fclose($fileOpen);
?>