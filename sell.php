<?php
    session_start();
    include_once('storage.php');

    $userStor = new Storage(new JsonIO('user.json'));
    $cardStor = new Storage(new JsonIO('data.json'));

    $user = $userStor->findOne(['fullname' => $_SESSION['fullname']]);
    $cardId = $_GET['id'];
    $card = $cardStor->findById($cardId);

    $sellPrice = $card['price'] * 0.9;

    $user['money'] += $sellPrice;

    $key = array_search($cardId, $user['cards']);
    if ($key !== false) {
        unset($user['cards'][$key]);
    }

    if ($user['id'] !== 0 && $user !== null) {
        $userStor->update($user['id'], $user);
    }

    unset($card['owner']);

    if ($cardId !== 0 && $card !== null) {
        $cardStor->update($cardId, $card);
    }
    $_SESSION['money'] = $user['money'];
    $_SESSION['cards'] = $user['cards'];
    // Redirect the user back to their profile
    header('Location: user.php');
?>