<?php
include 'includes/navigation.php';

//veriables for errors.
$inventoryError = "";
$nameError = "";
$selectError = "";
$agreeError = "";
$gameMatch = null;

//grabs server data after submit button be pressed
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $inventoryCode = $_POST['Icode'] ?? '';
    $gameName = $_POST['gameName'] ?? '';
    $consoles = $_POST['consoleSelect'] ?? '';
    $agree = isset($_POST['agree']);

    //preg matchs to see if the inventory code is put in properly.
    $pregid = "/^GAME-\d{4}$/";
    if (!preg_match($pregid, $inventoryCode)) {
        $inventoryError = "Invalid inventory code format. Expected format: GAME-1234";
    }

    if($gameName == ""){
        $nameError = "Please enter a game name.";
    }

    if($consoles == 0){
        $selectError = "Please select a console.";
    }

    if($agree == false){
        $agreeError = "Please agree to the price.";
    }

    $fileName = 'games/games.csv';

    if (empty($inventoryError) && empty($nameError) && empty($selectError) && $agree) {
        $fileName = 'games/games.csv';

        if (file_exists($fileName) && ($file = fopen($fileName, 'r')) !== false) {
            // Skip the CSV header row
            fgetcsv($file);

            while (($row = fgetcsv($file)) !== false) {
                // Compare row values (index 0 = code, index 1 = name)
                if (
                    strcasecmp($row[0], $inventoryCode) === 0 || 
                    strcasecmp($row[1], $gameName) === 0
                ) {
                    $gameMatch = $row; // Store the matching row
                    break; // Stop reading further rows
                }
            }
            fclose($file);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>index</title>
</head>
<body>
    
    <form method="post" id="format">
        <label>Inventory Code: </label>
        <input type="text" name="Icode" require placeholder="GAME-1234"><br>
        <label id="error"><?php echo($inventoryError); ?></label><br>

        <label>Video Game Name: </label>
        <input type="text" name="gameName" require><br>
        <label id="error"><?php echo($nameError); ?></label><br>

        <label>Console</label>
        <select name="consoleSelect" require>
            <option value="0">Select</option>
            <option value="xbox">Xbox</option>
            <option value="playstation">Playstation</option>
            <option value="steam">Steam</option>
            <option value="nintendo">Nintendo</option>
            <option value="mac">Mac</option>
        </select><br>
        <label id="error"><?php echo($selectError); ?></label><br>

        <label name="price" require>Price agreement: </label>
        <input type="checkbox" name="agree"><br>
        <label id="error"><?php echo($agreeError); ?></label><br>

        <input type="submit" name="submit">
    </form>

    <div id="outputs">
        <?php if ($gameMatch): ?>
        <div>
            <h3>Game Found:</h3>
            <p><strong>Inventory Code:</strong> <?php echo htmlspecialchars($gameMatch[0]); ?></p>
            <p><strong>Game Name:</strong> <?php echo htmlspecialchars($gameMatch[1]); ?></p>
            <p><strong>Console:</strong> <?php echo htmlspecialchars($gameMatch[2]); ?></p>
            <p><strong>Price:</strong> $<?php echo htmlspecialchars(number_format((float)$gameMatch[3], 2)); ?></p>
            <img src="<?php echo htmlspecialchars($gameMatch[4]); ?>" alt="Game Image" width="200">
        </div>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($inventoryError)): ?>
            <p>No matching game was found in the database.</p>
        <?php endif; ?>
    </div>

</body>
</html>