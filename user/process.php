<?php

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // If the calling script is so rude as to not use the proper method, don't bother to respond either (but let 'm know it was wrong)
    header( $_SERVER["SERVER_PROTOCOL"], true, 405);
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


$first_name = '';
$last_name = '';
$email = '';
$password = '';
$birthday = '';
$gender = '';

// Check if submit button is clicked
if (isset($_POST['register-submit'])) {

    // Check if the form input is not empty
    if (empty($_POST['first-name']) || empty($_POST['last-name']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['birthday']) || empty($_POST['gender'])) {
        header("Location: register.php?register=failed", true, 303);
        exit;
    } else {


        $firstname = $_POST['first-name'];
        $lastname = $_POST['last-name'];
        $gender = $_POST['gender'];

        // Check email
        $email = $_POST['email'];
        if (isValidEmail($email)) {
            header("Location: register.php?register=failed", true, 303);
            exit;
        }

        // TODO: Convert birthday to string to add to database, check radio button for gender and hash password and check general things
        $birthday = $_POST['birthday'];

        // Hash password
        $password = $_POST['password'];
        $password = $password = hashPassword($password);

        addUser($connection, 'users', $first_name, $last_name, $email, $password, $birthday, $gender);
    }
}



