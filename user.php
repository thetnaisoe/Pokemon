<?php 
    session_start();
    include_once('storage.php');
    $stor = new Storage(new JsonIO('user.json'));
    $user = $stor->findOne(['fullname' => $_SESSION['fullname']]);

    $cardStor = new Storage(new JsonIO('data.json'));
    //because array_map does not preserve keys but indexes.
    $cards = array_reduce($user['cards'], function($carry, $cardId) use ($cardStor) {
        if ($cardId !== null) {
            $carry[$cardId] = $cardStor->findById($cardId);
        }
        return $carry;
    }, []);
    /*
    $cards = array_map(function($cardId) use ($cardStor) {
        return $cardStor->findById($cardId);
    }, $user['cards']); */
    $colors = [
        'fire' => 'indianred',
        'electric' => 'lightyellow',
        'grass' => 'lightgreen',
        'water' => 'lightblue',
        'bug' => 'sandybrown',
        'normal' => 'lightgrey',
        'poison' => 'plum',
    ];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href=style.css>
</head>
<body>
    <div class="user">
        <h1>Profile Details</h1>
        <p>Fullname: <?= $user['fullname'] ?></p>
        <p>Email: <?= $user['email'] ?></p>
        <p>Money:💰<?= $user['money'] ?></p>
        <h2>Your Cards</h2>
        <div class="usercard">
            <?php foreach($cards as $cardId => $card): 
                $cardTypeColor = $colors[$card['type']] ?? 'white';?>
                <div class="card-item">
                    <div class="card">
                        <a href="card.php?id=<?= $cardId ?>">
                            <img src="<?= $card['image'] ?>" alt="<?= $card['name'] ?>" style="background-color: <?= $cardTypeColor ?>;">
                            <div class="card-body">
                                <h5 class="card-title"><?= $card['name'] ?></h5>
                                <p class="card-type">🔖 <?= $card['type'] ?></p>
                                <div class="card-attributes">
                                    <p class="card-text">❤️ <?= $card['hp'] ?></p>
                                    <p class="card-text">⚔️ <?= $card['attack'] ?></p>
                                    <p class="card-text">🔰 <?= $card['defense'] ?></p>
                                </div>
                                <p class="card-price">💰 <?= $card['price'] ?></p>
                            </div>
                        </a>
                    </div>
                    <a href="sell.php?id=<?= $cardId ?>">
                    <button><p>Sell</p></button>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <a href="index.php"><p>Back to main page</p></a>
    </div>
</body>
</html>