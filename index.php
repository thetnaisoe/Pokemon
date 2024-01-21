<?php session_start();
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <section class="header">
        <h1>IKemon</h1>
        <nav>
            <div class="nav-links" id="navLinks">
                <ul>
                    <?php if(isset($_SESSION['fullname'])): ?>
                        <?php if($_SESSION['fullname'] !== 'admin'): ?>
                            <li><a href="user.php">👤  <?= $_SESSION['fullname'] ?></a></li>
                            <li>💰<?= $_SESSION['money'] ?></li>
                        <?php else: ?>
                            <li>👤  <?= $_SESSION['fullname'] ?></li>
                            <li><a href="addCard.php">Create Card</a></li>
                        <?php endif; ?>
                        <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </section>

    <section class="filter-container">
        <form method="GET" action="index.php" novalidate>
            <select name="type">
                <option value="" <?= (!isset($_GET['type']) || $_GET['type'] === '') ? 'selected' : '' ?>>All</option>
                <?php foreach ($colors as $type => $color): ?>
                    <option value="<?= $type ?>" <?= (isset($_GET['type']) && $_GET['type'] === $type) ? 'selected' : '' ?>><?= ucfirst($type) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="Filter">
        </form>
    </section>
    <div class ="buy-random-container">             
        <?php if(isset($_SESSION['fullname']) && $_SESSION['fullname'] !== 'admin'): ?>
            <a href="buyRandom.php" class="buy-random"><p>Buy Random Card with 💰100</p></a>
        <?php endif; ?>
    </div>

    <section class="card-container-whole">
    <?php
        include_once('storage.php');
        $userStor = new Storage(new JsonIO('user.json'));
        $cardStor = new Storage(new JsonIO('data.json'));

        $cards = $cardStor->findAll();
        if (isset($_GET['type']) && $_GET['type'] !== '') {
            $cards = array_filter($cards, function($card) {
                return $card['type'] === $_GET['type'];
            });
        }
        $isAdmin = isset($_SESSION['fullname']) && $_SESSION['fullname'] === 'admin';
        foreach($cards as $id => $card) {
            //check for main page first when no one logged in and 2nd cond for user details
            if(!isset($_SESSION['fullname']) || (isset($_SESSION['fullname']) && !isset($card['owner']))) {
                $cardTypeColor = $colors[$card['type']] ?? 'white';
    ?>
    <div class="card-container">
        <div class="card">
                <img src="<?= $card['image'] ?>" alt="<?= $card['name'] ?>" style="background-color: <?= $cardTypeColor ?>;">
                <div class="card-body">
                    <a href="card.php?id=<?= $id ?>">
                        <h5 class="card-title"><?= $card['name'] ?></h5>
                    </a>
                    <p class="card-type">🔖 <?= $card['type'] ?></p>
                    <div class="card-attributes">
                        <p class="card-text">❤️ <?= $card['hp'] ?></p>
                        <p class="card-text">⚔️ <?= $card['attack'] ?></p>
                        <p class="card-text">🔰 <?= $card['defense'] ?></p>
                    </div>
                    <p class="card-price">💰 <?= $card['price'] ?></p>
                </div>
        </div>
        <?php if(isset($_SESSION['fullname']) && !$isAdmin): ?>
            <a href="buy.php?id=<?= $id ?>" class="buy-button"><p>Buy</p></a>
        <?php endif; ?>
        <?php if($isAdmin && !isset($card['owner'])): ?>
            <a href="modify.php?id=<?= $id ?>" class="buy-button"><p>Modify</p></a>
        <?php endif; ?>
    </div>
    <?php
            }
        }
    ?>
    </section>       
</body>
</html>

