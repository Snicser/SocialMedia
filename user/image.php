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


// Get the username and check if exits
$Username = $_SESSION['username'];
$UserId = $_SESSION['user_id'];
if (empty($Username) || empty($UserId)) {
    header('Location: login.php', true, 303);
    exit; // Make sure the code stops here
}


header('Content-Type: image/jpg; image/png; image/jpeg;');

if (isset($_GET['userid'])) {
    if ($_GET['userid'] == $UserId) {

        $details = getProfileDetails($connection, $_GET['userid']);

        $Username = $details[0]["username"];

        readfile('../../../../../social-media-uploads/' . $Username . '/' . $_GET['image']);
    }
} else {
    readfile('../../../../../social-media-uploads/' . $Username . '/' . $_GET['image']);
}




