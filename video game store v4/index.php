<?php
    include 'includes/navigation.php';
    //Error message veriables;
    $pregError = '';
    $nameError = '';
    $consoleError = '';
    $fileError = '';
    $priceError = '';

    //used to check if inputs are put in correctly
    $isGood = 0;

    $moved = false;
    $output = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST"){
        //grabs inputs
        $code = htmlspecialchars($_POST['gCode']) ?? null;
        $name = htmlspecialchars($_POST['gName']) ?? null;
        $console = htmlspecialchars($_POST['gConsole']) ?? null;
        $price = htmlspecialchars($_POST['gPrice'] ?? '');


        //checks code input with preg match
        $preg = "/^GAME-\d{4}$/";
        if (!preg_match($preg, $code)){
            $pregError = "Invalid game code please use GAME-1234.";
        } else{
            $isGood++;
        }

        //checks to see if game has a name
        if($name == ''){
            $nameError = "No name entered!";
        } else{
            $isGood++;
        }

        //checks console input
        if($console == 'select'){
            $consoleError = "Please select a console type!";
        } else{
            $isGood++;
        }

        //checks price input
        if($price == ''){
            $priceError = 'No price submitted!';
        } else{
            $isGood++;
        }

        //checks file input
        if($_FILES['gFile']['error'] === 0){
            $temp = $_FILES['gFile']['tmp_name'];
            $fileName = $_FILES['gFile']['name'];
            $path = 'uploads/' . $_FILES['gFile']['name'];

            $counter = 1;
            $fileInfo = pathinfo($fileName);
            $fileExt = isset($fileInfo['extension']) ? '.' . $fileInfo['extension'] : '';
            $fileBase = $fileInfo['filename'];

            while(file_exists($path)){
                $fileName = $fileBase . '_' . $counter . $fileExt;
                $path = 'uploads/' . $fileName;
                $counter++;
            }

            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }

            $moved = move_uploaded_file($temp, $path);
        }

        //checks to see if the file moved
        if($moved == true){
            $isGood++;
        } else{
            $fileError = "An error with the file upload has occured";
        }


        //checks to see if all 5 Inputs are good
        if($isGood == 5){
            //temperarily stores the inputs into an array so it can put them into the csv and pull out the data to the output
            $data = [$code, $name, $console, $price, $path];

            //writes to the csv file
            $openFile = fopen('games/games.csv', 'a');
            fputcsv($openFile, $data);
            fclose($openFile);


            $output = "Submitted successfully!";
        } else{
            $output = $pregError . "<br>" . $nameError . "<br>" . $consoleError . "<br>" . $priceError. "<br>" . $fileError;
        }


    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>video game store</title>
</head>
<body>
    <form method="post" enctype="multipart/form-data" id="theform">
            <label>Game Code</label>
            <input type="text" name="gCode" placeholder="GAME-1234" required><br><br>

            <label>Game Name</label>
            <input type="text" name="gName" required><br><br>

            <label>Console</label>
            <select name="gConsole" required>
                <option value="select">Select</option>
                <option value="xbox">xbox</option>
                <option value="playstation">playstation</option>
                <option value="nintendo">nintendo</option>
                <option value="steam">steam</option>
                <option value="pc">PC</option>
            </select><br><br>

            <label>Game Price</label>
            <input type="number" step="0.01" name="gPrice" placeholder="0" required><br><br>

            <label>Image Upload</label>
            <input type="file" name="gFile" required><br><br>

            <input type="submit">
    </form>

    <div>
        <?php
            if($isGood == 5){
                echo'<div id="outputbox">';
                echo($output);
                echo'</div>';
            } else{
                echo '<div id="errorsbox">';
                echo($output);
                echo '</div>';
            }
        ?>
    </div>
</body>
</html>