<?php
    session_start();
    unset($_SESSION['username']);
    unset($_SESSION['money']);
    unset($_SESSION['cards']);
    session_destroy();
    header('Location: index.php');
    exit;
?>