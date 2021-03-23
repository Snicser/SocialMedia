<?php

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // If the calling script is so rude as to not use the proper method, don't bother to respond either (but let 'm know it was wrong)
    // Normally user $_SERVER["SERVER_PROTOCOL"] but it is not enabled in XAMPP right now so to fix this use Location: ../
    header('Location: login.php', true, 405);
    exit;
}

// Get the stuff we need
require_once '../inc/database.php';
require_once '../inc/functions.php';

// Check connection
$connection = getDatabaseConnection();
if (!$connection) {
    header($_SERVER["SERVER_PROTOCOL"], true, 503);
    exit;
}

// Check if the submit button is click to update email adres
if (isset($_POST['update-user-profile']) && isset($_GET['userid'])) {

    $URL_USER_ID = $_GET['userid'] ?? 0;


    // Check if the form input is not empty
    if (empty($_POST['update-email'])) {
        header("Location: settings.php?update=failed", true, 303);
        exit;
    }

    $email = $_POST['update-email'] ?? 'Undefined@gmail.com';

    // Check email
    if (!isValidEmail($email)) {
        header("Location: settings.php?update=failed&email=notvalid", true, 303);
        exit;
    }

    if (updateProfileInformation($connection, $URL_USER_ID, $email)) {
        header('Location: settings.php?update=success&userid=' . $URL_USER_ID, true, 303);
    } else {
        header('Location: settings.php?update=failed&userid=' . $URL_USER_ID, true, 303);
    }
}


