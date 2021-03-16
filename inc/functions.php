<?php

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

// Start or continue a session
session_start();


/**
 * Adds an user to the database after registration
 *
 * @param $connection
 * @param string $firstName
 * @param string $lastName
 * @param string $email
 * @param string $password
 * @param string $birthday
 * @param string $gender
 * @param int $isAdmin
 * @return bool
 */
function registerUser($connection, string $firstName, string $lastName, string $email, string $password, string $birthday, string $gender, int $isAdmin): bool {
    try {
        $query = "INSERT INTO `users` (`first_name`, `last_name`, `email`, `password`, `birthday`, `gender`, `username`, `is_admin`) 
                VALUES (:first_name, :last_name, :email, :password, :birthday, :gender, :username, :is_admin);";

        $preparedStatement = $connection -> prepare($query);
        $preparedStatement -> bindParam(':first_name', $firstName, PDO::PARAM_STR);
        $preparedStatement -> bindParam(':last_name', $lastName, PDO::PARAM_STR);
        $preparedStatement -> bindParam(':email', $email, PDO::PARAM_STR);
        $preparedStatement -> bindParam(':password', $password, PDO::PARAM_STR);
        $preparedStatement -> bindParam(':birthday', $birthday, PDO::PARAM_STR);
        $preparedStatement -> bindParam(':gender', $gender, PDO::PARAM_STR);
        $preparedStatement -> bindParam(':username', $firstName, PDO::PARAM_STR);
        $preparedStatement -> bindParam(':is_admin', $isAdmin, PDO::PARAM_INT);
        $preparedStatement -> execute();

        return true;
    } catch (PDOException $exception) {
        echo sprintf("Something went wrong when trying to add the user to the database: %s", htmlspecialchars($exception->getMessage()));
    }

    return false;
}

function addUserProfile($connection): void {
    try {
        $query = "INSERT INTO `profile` (`user_me_id`, `user_follower_id`) VALUES (:user_me_id, :user_follower_id);";
        $preparedStatement = $connection -> prepare($query);
        $preparedStatement -> bindParam(":user_me_id", 1);
        $preparedStatement -> bindParam(":user_follower_id", 1);
    } catch (PDOException $exception) {
        echo $exception -> getMessage();
    }
}

/**
 * Uploads a post to the database
 *
 * @param $connection
 * A connection to the database
 * @param string $image
 * The image name
 * @param string $caption
 * Description of the post
 * @param int $likes
 * How many likes a post has
 * @param int $comments
 * How many comments a post has
 * @param string $uploadDate
 * The upload date
 * @param $userId
 * The ID of the user
 * @return bool Return TRUE if the insert was successful otherwise FALSE
 */
function uploadPost($connection, string $image, string $caption, int $likes, int $comments, string $uploadDate, int $userId): bool {
    try {
        $query = 'INSERT INTO `posts`(`image_path`, `caption`, `likes`, `comments_total`, `upload_date`, `user_id`)
                    VALUES (:image, :caption, :likes, :comments, :uploadDate, :userId)';

        $preparedStatement = $connection -> prepare($query);
        $preparedStatement -> bindParam(':image', $image, PDO::PARAM_STR);
        $preparedStatement -> bindParam(':caption', $caption, PDO::PARAM_STR);
        $preparedStatement -> bindParam(':likes', $likes, PDO::PARAM_INT);
        $preparedStatement -> bindParam(':comments', $comments, PDO::PARAM_INT);
        $preparedStatement -> bindParam(':uploadDate', $uploadDate, PDO::PARAM_STR);
        $preparedStatement -> bindParam(':userId', $userId, PDO::PARAM_STR);
        $preparedStatement -> execute();

        return true;
    } catch (PDOException $exception) {
        echo $exception -> getMessage();
    }

    return false;
}

/**
 * Hash the users password
 *
 * @param $password User's entered password
 * @return bool|string|null Returns the hashed password, or FALSE on failure, or null if the algorithm is invalid
 *
 */
function hashPassword($password): bool|string|null {
    return password_hash($password, PASSWORD_DEFAULT, ["options" => 10]);
}

/**
 * Checks if the user entered their correct password
 *
 * @param $connection
 * The connection to the database
 * @param $password
 * The user's entered password
 *
 * @param $username
 * The user's entered username
 * @return bool Returns TRUE if password is correct or FALSE if it is not
 */
function checkUserPassword($connection, $password, $username): bool {
    // TODO REMEMBER ME CHECKBOX IS CHECKED

    /*
     * Maybe if more time make other query to get the user_id on valid credentials
     * We store the user_id in session variable because there is currently no other option / solution
     * We use the user_id to insert a post in the database
     */

    // Get the user credentials from the database
    $userCredentials = [];
    $query = "SELECT password, user_id FROM `users` WHERE username = :username";
    $preparedStatement = $connection -> prepare($query);
    $preparedStatement -> bindParam(':username', $username, PDO::PARAM_STR, 30);
    $preparedStatement -> execute();

    // Put them in an array
    while ($row = $preparedStatement -> fetch(PDO::FETCH_ASSOC)) {
        $userCredentials[] = $row;
    }

    // Check if array is empty if so return FALSE
    if (empty($userCredentials)) {
        return false;
    }

    foreach ($userCredentials as $credential) {
        $_SESSION['user_id'] = $credential['user_id'];
    }

    return true;

//    // Check if they match
//    if (password_verify($password, $row)) {
//        return true;
//    }

//    return false;
}

/**
 * Create directory folder for the user
 *
 * @param $dirName
 * Directory name for creating new directory
 */
function makeUserDirectory($dirName): void {
    mkdir('../../../../../social-media-uploads/' . strtolower($dirName), 0777, true);
}

/**
 * Checks if the entered email is a valid/correct email
 *
 * @param $email User's entered email address
 * @return bool Returns TRUE if email is valid or FALSE if it is not a valid email address
 */
#[Pure] function isValidEmail($email): bool {
    // Remove all illegal characters from email
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Validate e-mail
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }

    return false;
}

#[ArrayShape(['error' => "null"])] function uploadFile($file): array {

    $output = ['error' => null];

    //Set file upload path
    $PATH = '../../../../../social-media-uploads/';
    $TARGET_FILE = $PATH . basename($_FILES[$file]["name"]);

    //Set max file size in bytes
    $MAX_UPLOAD_SIZE = 8000000;
    $BYTES_TO_MB = 1000000;

    // File extension whitelist
    $WHITELIST_EXTENSIONS = ['jpeg', 'jpg', 'png'];

    // File type whitelist
    $WHITELIST_TYPE = ['image/jpeg', 'image/jpg', 'image/png'];

    // Check if there is a file
    if ( (!empty($_FILES[$file])) && ($_FILES[$file]['error'] == 0) ) {

        // Get file extension
        $FILE_EXTENSION = strtolower(pathinfo($TARGET_FILE, PATHINFO_EXTENSION));
        $FILE_TYPE = $_FILES[$file]["type"];

        // Check if the was uploaded via HTTP POST
        if (!is_uploaded_file($_FILES[$file]['tmp_name'])) {
            $output['error'][] = "Er ging iets fout";
            return $output;
        }

        // Check if image
        if (!getimagesize($_FILES[$file]['tmp_name'])) {
            $output['error'][] = "Het bestand is geen afbeelding";
            return $output;
        }

        //Check file has the right extension
        if (!in_array($FILE_EXTENSION, $WHITELIST_EXTENSIONS)) {
            $output['error'][] = "Deze extensie is niet toegestaan";
            return $output;
        }

        // Check that the file is of the right type
        if (!in_array($FILE_TYPE, $WHITELIST_TYPE)) {
            $output['error'][] = "Deze bestands type is niet toegestaan";
            return $output;
        }

        // Check that the file is not too big
        if ($_FILES[$file]["size"] > $MAX_UPLOAD_SIZE) {
            $output['error'][] = "Het bestand is te groot! Max: " . ($MAX_UPLOAD_SIZE / $BYTES_TO_MB) . " MB";
            return $output;
        }

        // Check if file already exists on server
        if (file_exists($TARGET_FILE)) {
            $output['error'][] = "Een bestand met deze naam bestaat al";
            return $output;
        }

        // Success
        // TODO: add to the user folder in social-media
        if (move_uploaded_file($_FILES[$file]['tmp_name'], $TARGET_FILE)) {
            $output['name'][] = basename($_FILES[$file]["name"]);
            return $output;
        }

        $output['error'][] = "Server Error!";

    } else {
        $output['error'][] = "Er is geen bestand geupload";
    }

    return $output;
}

