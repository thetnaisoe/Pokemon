<?php 
    session_start();
    include_once('storage.php');
    $userStor = new Storage(new JsonIO('user.json'));
    $cardStor = new Storage(new JsonIO('data.json'));

    $user = $userStor->findOne(['fullname' => $_SESSION['fullname']]);
    $cardId = $_GET['id'];
    $card = $cardStor->findById($cardId);

    if($user['money'] < $card['price']) {
        echo "You don't have enough money to buy this card.";
    } elseif(count($user['cards']) >= 5) {
        echo "You can't own more than 5 cards.";
    } else {
        $user['money'] -= $card['price'];
        $user['cards'][] = $cardId;
        $userStor->update($user['id'], $user);

        $card['owner'] = $user['id'];
        $cardStor->update($cardId, $card);

        //update the session data
        $_SESSION['money'] = $user['money'];
        $_SESSION['cards'] = $user['cards'];

        echo "You have successfully bought the card.";
    }
    echo '<br><a href="index.php">Go back to main page</a>';
?>