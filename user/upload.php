<?php

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // If the calling script is so rude as to not use the proper method, don't bother to respond either (but let 'm know it was wrong)
    // Normally user $_SERVER["SERVER_PROTOCOL"] but it is not enabled in XAMPP right now so to fix this use Location: ../
    header('Location: ../', true, 405);
    exit;
}

// Get the stuff we need
require_once ('../inc/database.php');
require_once ('../inc/functions.php');

// Check connection
$connection = getDatabaseConnection();
if (!$connection) {
    header($_SERVER["SERVER_PROTOCOL"], true, 503);
    exit;
}

// Check if the post message button was clicked
if (isset($_POST['post-message'])) {

    // Check if they passed a caption
    if (empty($_POST['caption'])) {
        header("Location: ../index.php?post=failed&error=caption", true, 303);
        exit;
    }

    // Check
    $caption = $_POST['caption'] ?? '-';
    $file = uploadFile($connection, 'user-file');
    $imageName = '';


    // Check if there was error if so, send a header with information
    if (is_array($file['error'])) {
        $errorMessage = join('&error=', $file['error']);
        $errorMessageURLEncoded = urlencode($errorMessage);
        header('Location: ../index.php?post=failed&error=' . $errorMessageURLEncoded , true, 303);
        exit;
    }

    if (is_array($file['name'])) {
        foreach ($file['name'] as $names) {
            $imageName = $names;
        }
    }

    if(!isset($_SESSION['user_id'])) {
        header('Location: ../index.php?post=failed&error=userid', true, 303);
        exit;
    }

    // Get date time and format to string
    $dateTime = new DateTime();
    $dateTime = $dateTime -> format('Y-m-d H:i:s');

    // Get the user id form the session
    $userId = $_SESSION['user_id'];

    if (!uploadPost($connection, $imageName, $caption, 0, 0, $dateTime, $userId)) {
        header('Location: ../index.php?post=failed&error=server', true, 303);
        exit;
    }

    header('Location: ../index.php?post=success', true, 303);
}





