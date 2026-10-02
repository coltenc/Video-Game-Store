<?php
include 'includes/navigation.php';

$games= [
    ["inventory code","game name","console","price","image"],
    ["GAME-1000","Minecraft","Nintendo",19.99,"uploads/minecraft logo.jpg"],
    ["GAME-1001","Call of duty","Steam",59.99,"uploads/call of duty logo.jpg"],
    ["GAME-1002","Hytale","Playstation",29.99,"uploads/hytale logo.jpg"],
    ["GAME-1003","Palworld","Xbox",19.99,"uploads/palworld logo.jpg"],
    ["GAME-1004","Pokemon","Nintendo",59.99,"uploads/pokemon logo.jpg"],
    ["GAME-1005","The Sims 4","Steam",39.99,"uploads/the sims 4 logo.png"],
    ["GAME-1006","Terraria","Playstation",19.99,"uploads/terraria logo.jpg"],
    ["GAME-1007","Stardew Valley","Mac",14.99,"uploads/stardew valley logo.jpg"]
];

    $fileName = 'games/games.csv';

    $file = fopen($fileName, 'w');
    if ($file !== false) {
        foreach ($games as $row) {
            fputcsv($file, $row);
        }
    fclose($file);
}

    if(file_exists($fileName)){
        if(($file = fopen($fileName, 'r')) !== false){
            while(($row = fgetcsv($file)) !== false){
                echo("<div id='games'>");
                echo "Inventory Code: " . htmlspecialchars($row[0]) . "<br>";
                echo "Game Name: " . htmlspecialchars($row[1]) . "<br>";
                echo "Console: " . htmlspecialchars($row[2]) . "<br><br>";
                echo "Price: $" . htmlspecialchars(number_format((float)$row[3], 2)) . "<br>";
                echo "<img src='" . htmlspecialchars($row[4]) . "' alt='Game Image' width='200'><br><br>";
                echo("</div>");
            }

            fclose($file);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Document</title>
</head>
<body>
</body>
</html>