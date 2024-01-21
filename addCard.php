<?php
    session_start();
    include_once('storage.php');

    // Check if the user is an admin
    if (!isset($_SESSION['fullname']) || $_SESSION['fullname'] !== 'admin') {
        // If not, redirect them to the index page
        header('Location: index.php');
        exit;
    }

    $cardStor = new Storage(new JsonIO('data.json'));

    $name = $_POST['name'] ?? '';
    $type = strtolower($_POST['type'] ?? '');
    $hp = $_POST['hp'] ?? '';
    $attack = $_POST['attack'] ?? '';
    $defense = $_POST['defense'] ?? '';
    $price = $_POST['price'] ?? '';
    $description = $_POST['description'] ?? '';
    $image = $_POST['image'] ?? '';

    $errors = [];
    if ($_POST) {

        if (empty($name) || empty($type) || empty($hp) || empty($attack) || empty($defense) || empty($price) || empty($description) || empty($image)) {
            $errors[] = 'All fields must be filled.';
        }

        if (!ctype_alpha(str_replace(' ', '', $name))) {
            $errors[] = 'Name must contain only letters and spaces.';
        }
    
        if (!ctype_alpha(str_replace(' ', '', $type))) {
            $errors[] = 'Type must contain only letters and spaces.';
        }

        if (!$errors) {
            $cardStor->add([
                'name' => $name,
                'type' => $type,
                'hp' => $hp,
                'attack' => $attack,
                'defense' => $defense,
                'price' => $price,
                'description' => $description,
                'image' => $image,
            ]);
            //$success = 'Card created successfully.';
            header('Location: index.php');
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Card</title>
    <link rel="stylesheet" href="style.css">
</head>=
<body class="background">
    <div class="create">
        <h1>Add Card</h1>
        <div class="error">
            <?php 
                foreach($errors as $error){
                    echo $error . '<br>';
                }
            ?>
        </div>
        <form action="addCard.php" method="post" novalidate>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= $name ?>">
            <label for="type">Type:</label>
            <input type="text" id="type" name="type" value="<?= $type ?>">
            <label for="hp">HP:</label>
            <input type="number" id="hp" name="hp" value="<?= $hp ?>">
            <label for="attack">Attack:</label>
            <input type="number" id="attack" name="attack" value="<?= $attack ?>">
            <label for="defense">Defense:</label>
            <input type="number" id="defense" name="defense" value="<?= $defense ?>">
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" value="<?= $price ?>">
            <label for="description">Description:</label>
            <textarea id="description" name="description" value="<?= $description ?>"></textarea>
            <label for="image">Image URL:</label>
            <input type="text" id="image" name="image" value="<?= $image ?>">
            <button type="submit">Add Card</button>
        </form>
    </div>
</body>
</html>