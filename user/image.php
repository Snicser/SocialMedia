<?php

// Get the stuff we need
require_once '../inc/database.php';
require_once '../inc/functions.php';

// Check if the user is logged in
if ((!isset($_SESSION['logged-in-user'])) || (!isset($_SESSION['user_id'])) || empty($_SESSION['logged-in-user']) || empty($_SESSION['user_id'])) {
    header('Location: login.php', true, 303);
    exit; // Make sure the code stops here
}


// Check connection
$connection = getDatabaseConnection();
if (!$connection) {
    header($_SERVER["SERVER_PROTOCOL"], true, 503);
    exit;
}


header('Content-Type: image');
if (isset($_GET['userid']) && isset($_GET['username']) && isset($_GET['image'])) {
    readfile('../../../../../social-media-uploads/' . $_GET['username'] . '/' . $_GET['image'], true);
}


//if (isset($_GET['userid'])) {
//    if ($_GET['userid'] == $UserId) {
//
//        $details = getProfileDetails($connection, $_GET['userid']);
//
//        $Username = $details[0]["username"];
//
//        readfile('../../../../../social-media-uploads/' . $Username . '/' . $_GET['image']);
//    }
//} else {
//    readfile('../../../../../social-media-uploads/' . $Username . '/' . $_GET['image']);
//}




