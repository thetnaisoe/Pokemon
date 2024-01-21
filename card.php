<?php

$data = json_decode(file_get_contents('data.json'), true);
$id = $_GET['id'] ?? -1;

if(!isset($data[$id])){
    header('location: index.php');
}

$card = $data[$id] ?? null;

$colors = [
    'fire' => 'indianred',
    'electric' => 'lightyellow',
    'grass' => 'lightgreen',
    'water' => 'lightblue',
    'bug' => 'sandybrown',
    'normal' => 'lightgrey',
    'poison' => 'plum',
    'darkness' => 'darkgrey',
];

$color = 'white'; // Default to white

if ($card !== null && isset($card['type'])) {
    $color = $colors[$card['type']] ?? 'white';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-color: <?php echo $color; ?>;">
    <div class="details">
    <?php if ($card !== null): ?>
        <h1><?php echo $card['name']; ?></h1>
        <img src="<?php echo $card['image']; ?>" alt="<?php echo $card['name']; ?>">
        <p>Type: <?php echo $card['type']; ?></p>
        <p>HP: <?php echo $card['hp']; ?></p>
        <p>Attack: <?php echo $card['attack']; ?></p>
        <p>Defense: <?php echo $card['defense']; ?></p>
        <p>Price: <?php echo $card['price']; ?></p>
        <p>Description: <?php echo $card['description']; ?></p>
    <?php else: ?>
        <h1>Card not found</h1>
    <?php endif; ?>
    <a href="index.php"><p>Back to main page</p></a>
    </div>
</body>
</html>
