<?php

session_start();
include_once('storage.php');

$userStor = new Storage(new JsonIO('user.json'));
$cardStor = new Storage(new JsonIO('data.json'));

$cards = $cardStor->findAll();
$availableCards = array_filter($cards, function($card) {
    return !isset($card['owner']);
});

if (empty($availableCards)) {
    echo "All cards have been bought.";
    echo '<br><a href="index.php">Go back to main page</a>';
    exit;
}

$cardIds = array_keys($availableCards); // Get all card IDs
$randomCardId = $cardIds[array_rand($cardIds)]; // Select a random card ID

$user = $userStor->findOne(['fullname' => $_SESSION['fullname']]);

if ($user['money'] < 100) {
    echo "You don't have enough money to buy this card.";
    echo '<br><a href="index.php">Go back to main page</a>';
} elseif (count($user['cards']) >= 5) {
    echo "You can't own more than 5 cards.";
    echo '<br><a href="index.php">Go back to main page</a>';
} else {
    $user['money'] -= 100;
    // Ensure that the 'cards' array is not a multidimensional array
    if (isset($user['cards']) && is_array($user['cards'])) {
        $user['cards'] = array_map('strval', $user['cards']);
    } else {
        $user['cards'] = [];
    }

    $user['cards'][] = $randomCardId;
    $userStor->update($user['id'], $user);

    $card = $cards[$randomCardId];
    $card['owner'] = $user['id'];
    $cardStor->update($randomCardId, $card);

    $_SESSION['money'] = $user['money'];
    $_SESSION['cards'] = $user['cards'];

    header('Location: user.php');
    exit;
}
?>
