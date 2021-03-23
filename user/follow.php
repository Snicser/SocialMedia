<?php

// Get the stuff we need
require_once ('../inc/database.php');
require_once ('../inc/functions.php');

// Check connection
$connection = getDatabaseConnection();
if (!$connection) {
    header($_SERVER["SERVER_PROTOCOL"], true, 503);
    exit;
}

// Get the userID of the profile
if (isset($_GET['userid'])) {
    $URL_USER_ID = $_GET['userid'] ?? 0;
}


// Get the userID of the current user
$userId = $_SESSION['user_id'] ?? 0;

// userMe -> current user
// user_follower --> URL_USER_ID




