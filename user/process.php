<?php

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // If the calling script is so rude as to not use the proper method, don't bother to respond either (but let 'm know it was wrong)
    // Normally user $_SERVER["SERVER_PROTOCOL"] but it is not enabled in XAMPP right now so to fix this use Location: ../
    header('Location: ../index.php', true, 405);
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

// Check if submit button is clicked for registration
if (isset($_POST['register-submit'])) {

    // Check if the form input is not empty
    if (empty($_POST['first-name']) || empty($_POST['last-name']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['birthday']) || empty($_POST['gender'])) {
        header("Location: register.php?register=failed", true, 303);
        exit;
    }

    // Get the data from the input fields
    $firstName = $_POST['first-name'] ?? 'Undefined';
    $lastName = $_POST['last-name'] ?? 'Undefined';
    $email = $_POST['email'] ?? 'Undefined@gmail.com';
    $password = $_POST['password'] ?? 'Undefined';
    $birthday = $_POST['birthday'] ?? 'Undefined';
    $gender = $_POST['gender'] ?? 'Undefined';

    // Check email
    if (!isValidEmail($email)) {
        header("Location: register.php?register=email", true, 303);
        exit;
    }

    // Hash password
    $password = hashPassword($password);

    // Add the user to the database
    if (!registerUser($connection, $firstName, $lastName, $email, $password, $birthday, $gender, 0)) {
        header("Location: register.php?register=servererror");
    }

    // Make directory and send the user to login page
    makeUserDirectory($firstName);
    header("Location: login.php?register=success", true, 303);
}

// Check if submit button is clicked for login
if (isset($_POST['login'])) {

    // Check if the form input is not empty
    if (empty($_POST['username']) || empty($_POST['password'])) {
        header("Location: login.php?login=failed", true, 303);
        exit;
    } else {

        $username = $_POST['username'] ?? "Undefined";
        $password = $_POST['password'] ?? "Undefined";


        // Check user login
        if (checkUserPassword($connection, $password, $username)) {
            header("Location: ../index.php?login=success", true, 303);
        }

        header("Location: login.php?login=failed", true, 303);
    }
}



