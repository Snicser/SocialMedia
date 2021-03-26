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
function registerUser(PDO $connection, string $firstName, string $lastName, string $email, string $password, string $birthday, string $gender, int $isAdmin): bool {
    try {
        $query = "INSERT INTO `users` (`first_name`, `last_name`, `email`, `password`, `birthday`, `gender`, `username`, `is_admin`) 
                VALUES (:first_name, :last_name, :email, :password, :birthday, :gender, :username, :is_admin);";

        $lowerCaseUsername = strtolower($firstName);
        $preparedStatement = $connection -> prepare($query);
        $preparedStatement -> bindParam(':first_name', $firstName);
        $preparedStatement -> bindParam(':last_name', $lastName);
        $preparedStatement -> bindParam(':email', $email);
        $preparedStatement -> bindParam(':password', $password);
        $preparedStatement -> bindParam(':birthday', $birthday);
        $preparedStatement -> bindParam(':gender', $gender);
        $preparedStatement -> bindParam(':username', $lowerCaseUsername);
        $preparedStatement -> bindParam(':is_admin', $isAdmin);
        $preparedStatement -> execute();

        return true;
    } catch (PDOException $exception) {
        echo sprintf("Something went wrong when trying to add the user to the database: %s", htmlspecialchars($exception->getMessage()));
    }

    return false;
}

function addUserProfile(PDO $connection): void {
    try {
        $query = "INSERT INTO `profile` (`user_me_id`, `user_follower_id`) VALUES (:user_me_id, :user_follower_id);";
        $preparedStatement = $connection -> prepare($query);
        $i = 1; // TODO: Make this beter
        $preparedStatement -> bindParam(":user_me_id", $i);
        $preparedStatement -> bindParam(":user_follower_id", $i);
    } catch (PDOException $exception) {
        echo sprintf("Something went wrong when trying to add the user to the database: %s", htmlspecialchars($exception->getMessage()));
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
function uploadPost(PDO $connection, string $image, string $caption, int $likes, int $comments, string $uploadDate, int $userId): bool {
    try {
        $query = 'INSERT INTO `posts`(`image_path`, `caption`, `likes`, `comments_total`, `upload_date`, `user_id`)
                    VALUES (:image, :caption, :likes, :comments, :uploadDate, :userId)';

        $preparedStatement = $connection -> prepare($query);
        $preparedStatement -> bindParam(':image', $image);
        $preparedStatement -> bindParam(':caption', $caption);
        $preparedStatement -> bindParam(':likes', $likes);
        $preparedStatement -> bindParam(':comments', $comments);
        $preparedStatement -> bindParam(':uploadDate', $uploadDate);
        $preparedStatement -> bindParam(':userId', $userId);
        $preparedStatement -> execute();

        return true;
    } catch (PDOException $exception) {
        echo sprintf("Something went wrong when trying to add the user to the database: %s", htmlspecialchars($exception->getMessage()));
    }

    return false;
}

// View the post from all the following people
function getPostFromFollowingUsers(PDO $connection, int $userId): array {
    $posts = [];
    $followers = [];

    try {
        $query = 'SELECT `user_follower_id` FROM `profile` WHERE `user_me_id` = :userIdMe';
        $preparedStatement = $connection -> prepare($query);
        $preparedStatement -> bindParam(':userIdMe', $userId);
        $preparedStatement -> execute();

        while ($row = $preparedStatement -> fetchAll(PDO::FETCH_ASSOC)) {
            $followers = $row; // Array wit 8 and 10
        }

        if (empty($followers)) {
            return $posts;
        }


        $query = 'SELECT `posts`.*, `users`.`username` FROM `posts` 
                LEFT JOIN `users` ON `posts`.`user_id` = `users`.`user_id` 
            WHERE `posts`.`user_id` IN (' . implode(',', array_column($followers, 'user_follower_id')) . ')';

        $preparedStatement = $connection -> prepare($query);
        $preparedStatement -> execute();


        while ($row = $preparedStatement -> fetchAll(PDO::FETCH_ASSOC)) {
            $posts = $row; // Array with image path and followers
        }

        return $posts;

    } catch (PDOException $exception) {
        echo sprintf("Something went wrong when trying to get posts: %s", htmlspecialchars($exception->getMessage()));
    }

    return $posts;
}

/**
 * Get the profile posts and name from the user
 *
 * @param PDO $connection Instance of PDO connection object
 * @param int $userId User ID
 * @return array Returns a array containing information about the users profile
 */
function getProfilePostsAndName(PDO $connection, int $userId): array {
    $profilePosts = [];

    try {
        // Return post_id, image_path, likes, comments_total, user_id, username
        $query = 'SELECT `posts`.`post_id`, `posts`.`image_path`,  `posts`.`likes`, `posts`.`comments_total`, `posts`.`user_id`, `users`.`username` FROM `posts` 
	                LEFT JOIN `users` ON `posts`.`user_id` = `users`.`user_id` WHERE `users`.`user_id` = :userId';
        $preparedStatement = $connection -> prepare($query);
        $preparedStatement -> bindParam(':userId', $userId);
        $preparedStatement -> execute();

        // Put them in an array
        while ($row = $preparedStatement -> fetch(PDO::FETCH_ASSOC)) {
            $profilePosts[] = $row;
        }

    } catch (PDOException $exception) {
        echo sprintf("Something went wrong when trying to get posts: %s", htmlspecialchars($exception->getMessage()));
    }

    return $profilePosts;
}

function getProfileName(PDO $connection, int $userId): array {
    $profileName = [];

    try {

        $query = 'SELECT username FROM users WHERE user_id = :userId';
        $preparedStatement = $connection -> prepare($query);
        $preparedStatement -> bindParam(':userId', $userId);
        $preparedStatement -> execute();

        return $profileName = $row = $preparedStatement -> fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $exception) {
        echo sprintf("Something went wrong when trying to get the profile name: %s", htmlspecialchars($exception->getMessage()));
    }

    return $profileName;
}

function getUserInformation(PDO $connection, int $userId): array {
    $userInformation = [];

    try {
        $query = 'SELECT `users`.`first_name`, `users`.`last_name`, `users`.`email` FROM `users` WHERE user_id = :userId';
        $preparedStatement = $connection -> prepare($query);
        $preparedStatement -> bindParam(':userId', $userId);
        $preparedStatement -> execute();

        while ($row = $preparedStatement -> fetch(PDO::FETCH_ASSOC)) {
            $userInformation = $row;
        }

        return $userInformation;

    } catch (PDOException $exception) {
        echo sprintf("Something went wrong when trying to get your information: %s", htmlspecialchars($exception->getMessage()));
    }

    return $userInformation;
}

function updateProfileInformation(PDO $connection, int $userId, string $email): bool {
    try {
        $query = 'UPDATE `users` SET `email` = :email WHERE `users`.`user_id` = :userId';
        $preparedStatement = $connection -> prepare($query);
        $preparedStatement -> bindParam(':email', $email);
        $preparedStatement -> bindParam(':userId', $userId);
        $preparedStatement -> execute();
        return true;
    } catch (PDOException $exception) {
        echo sprintf("Something went wrong when trying to update your profile: %s", htmlspecialchars($exception->getMessage()));
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
function hashPassword(string $password): bool|string|null {
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
function checkUserPassword(PDO $connection, string $password, string $username): bool {
    // TODO REMEMBER ME CHECKBOX IS CHECKED

    /*
     * Maybe if more time make other query to get the user_id on valid credentials
     * We store the user_id in session variable because there is currently no other option / solution
     * We use the user_id to insert a post in the database
     */

    // Default variables
    $userCredentials = [];
    $userPassword = '';
    $username = strtolower($username);

    try {
        // Get the user credentials from the database
        $query = "SELECT password, user_id FROM `users` WHERE username = :username";
        $preparedStatement = $connection -> prepare($query);
        $preparedStatement -> bindParam(':username', $username);
        $preparedStatement -> execute();

        // Put them in an array
        while ($row = $preparedStatement -> fetch(PDO::FETCH_ASSOC)) {
            $userCredentials[] = $row;
        }
    } catch (PDOException $exception) {
        echo sprintf("Something went wrong when trying to check your password :%s", htmlspecialchars($exception->getMessage()));
    }

    // Check if array is empty if so return FALSE
    if (empty($userCredentials)) {
        return false;
    }

    // Loop through the array to get the values
    foreach ($userCredentials as $credential) {
        $_SESSION['user_id'] = $credential['user_id'];
        $userPassword = $credential['password'];
    }

    // Check if they match
    if (password_verify($password, $userPassword)) {
        $_SESSION['logged-in-user'] = true;
        return true;
    }

    return false;
}

/**
 * Create directory folder for the user
 *
 * @param $dirName
 * Directory name for creating new directory
 */
function makeUserDirectory(string $dirName): void {
    mkdir('../../../../../social-media-uploads/' . strtolower($dirName), 0777, true);
}

/**
 * Checks if the entered email is a valid/correct email
 *
 * @param $email User's entered email address
 * @return bool Returns TRUE if email is valid or FALSE if it is not a valid email address
 */
#[Pure] function isValidEmail(string $email): bool {
    // Remove all illegal characters from email
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Validate e-mail
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }

    return false;
}

#[ArrayShape(['error' => "null"])] function uploadFile(PDO $connection, string $file): array {

    $output = ['error' => null];
    $row = '';

    try {
        // Get the username
        $userId = $_SESSION['user_id'];
        if (empty($userId)) {
            $output['error'][] = "Er ging iets fout met een gebruikers ID.";
            return $output;
        }

        $query = "SELECT username FROM `users` WHERE user_id = :user_id";
        $preparedStatement = $connection -> prepare($query);
        $preparedStatement -> bindParam(':user_id', $userId);
        $preparedStatement -> execute();

        // Put the username in the $row variable
        $row = $preparedStatement -> fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $exception) {
        echo sprintf("Something went wrong when trying to get the username: %s", htmlspecialchars($exception->getMessage()));
    }

    if (empty($row)) {
        $output['error'][] = "Er ging is een onbekende fout opgetreden.";
        return $output;
    }

    // Set username into the session
    $_SESSION['username'] = $row['username'];

    //Set file upload path
    $PATH = '../../../../../social-media-uploads/' . $row['username'];
    $TARGET_FILE = $PATH . '/' . basename($_FILES[$file]["name"]);

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

/**
 * Insert the following people in the database
 *
 * @param PDO $connection Instance of PDO connection object
 * @param int $userIdMe User ID from the session
 * @param int $userToFollowId User ID from the URL
 * @return bool Return TRUE if the insert was successful otherise FALSE
 */
function followUser(PDO $connection, int $userIdMe, int $userToFollowId): bool {
    try {
        $query = 'INSERT INTO `profile` (`user_me_id`, `user_follower_id`) VALUES (:userIdMe , :userToFollowId)';
        $preparedStatement = $connection->prepare($query);
        $preparedStatement -> bindParam(':userIdMe', $userIdMe);
        $preparedStatement -> bindParam(':userToFollowId', $userToFollowId);
        $preparedStatement -> execute();

        return true;
    } catch (PDOException $exception) {
        echo sprintf("Something went wrong when try to follow the other user: %s", htmlspecialchars($exception->getMessage()));
    }

    return false;
}

/**
 * Get the following count
 *
 * @param PDO $connection Instance of PDO connection object
 * @param int $userIdMe User ID from the session
 * @return int Return the amount of people who the user is following
 */
function getFollowingCount(PDO $connection, int $userIdMe): int {
    try {
        $query = 'SELECT DISTINCT COUNT(`user_me_id`) FROM `profile` WHERE user_me_id = :userIdMe';
        $preparedStatement = $connection->prepare($query);
        $preparedStatement -> bindParam(':userIdMe', $userIdMe);
        $preparedStatement -> execute();

        $row = $preparedStatement -> fetch(PDO::FETCH_ASSOC);

        foreach ($row as $value) {
            $following = $value;
        }

        return $following;
    } catch (PDOException $exception) {
        echo sprintf("Something went wrong when try to get following count: %s", htmlspecialchars($exception->getMessage()));
    }

    return -1;
}

/**
 * Get the follower count
 *
 * @param PDO $connection Instance of PDO connection object
 * @param int $userIdMe User ID from the session
 * @return int Return the amount of people who follows the user
 */
function getFollowersCount(PDO $connection, int $userIdMe): int {
    try {
        $query = 'SELECT DISTINCT COUNT(`user_follower_id`) FROM `profile` WHERE user_follower_id = :userIdMe';
        $preparedStatement = $connection->prepare($query);
        $preparedStatement -> bindParam(':userIdMe', $userIdMe);
        $preparedStatement -> execute();

        $row = $preparedStatement -> fetch(PDO::FETCH_ASSOC);

        foreach ($row as $value) {
            $following = $value;
        }

        return $following;
    } catch (PDOException $exception) {
        echo sprintf("Something went wrong when try to get follower count: %s", htmlspecialchars($exception->getMessage()));
    }

    return -1;
}

/**
 * Get the post upload count
 *
 * @param PDO $connection Instance of PDO connection object
 * @param int $userIdMe User ID from the session
 * @return int Return the amount of posts uploaded by the user
 */
function getPostUploadedCount(PDO $connection, int $userIdMe): int {
    try {
        $query = 'SELECT DISTINCT COUNT(`user_id`) FROM posts WHERE user_id = :userIdMe';
        $preparedStatement = $connection->prepare($query);
        $preparedStatement -> bindParam(':userIdMe', $userIdMe);
        $preparedStatement -> execute();

        $row = $preparedStatement -> fetch(PDO::FETCH_ASSOC);

        foreach ($row as $value) {
            $following = $value;
        }

        return $following;
    } catch (PDOException $exception) {
        echo sprintf("Something went wrong when try to get the post count: %s", htmlspecialchars($exception->getMessage()));
    }

    return -1;
}

function addComment(PDO $connection, string $comment, $likes, $userId, $postId): bool {
    try {
        $query = 'INSERT INTO `comments` (`comment`, `comment_like`, `user_id`, `post_id`) VALUES (:comment, :likes, :userId, :postId)';
        $preparedStatement = $connection -> prepare($query);
        $preparedStatement -> bindParam(':comment', $comment);
        $preparedStatement -> bindParam(':likes', $likes);
        $preparedStatement -> bindParam(':userId', $userId);
        $preparedStatement -> bindParam(':postId', $postId);
        $preparedStatement->execute();

        return true;
    } catch (PDOException $exception) {
        echo sprintf("Something went wrong when trying to add a comment: %s", htmlspecialchars($exception->getMessage()));
    }

    return false;
}

function getCommentsFromPost(PDO $connection, $postId): array {
    $comments = [];

    try {
        $query = 'SELECT `comments`.*, `users`.`username` FROM `comments` 
                    LEFT JOIN `users` ON `comments`.`user_id` = `users`.`user_id`
                    WHERE `comments`.`post_id` = :postId';
        $preparedStatement = $connection -> prepare($query);
        $preparedStatement -> bindParam(':postId', $postId);
        $preparedStatement->execute();

        while ($row = $preparedStatement -> fetchAll(PDO::FETCH_ASSOC)) {
            $comments = $row;
        }

        return $comments;

    } catch (PDOException $exception) {
        echo sprintf("Something went wrong when trying to get the comments: %s", htmlspecialchars($exception->getMessage()));
    }

    return $comments;
}

function searchProfiles(PDO $connection, string $username): array {
    $profiles = [];

    try {
        $query = 'SELECT user_id, username FROM `users` WHERE username = :username';
        $preparedStatement = $connection -> prepare($query);
        $preparedStatement -> bindParam(':username', $username);
        $preparedStatement -> execute();

        while ($row = $preparedStatement -> fetchAll(PDO::FETCH_ASSOC)) {
            $profiles = $row;
        }

        return $profiles;
    } catch (PDOException $exception) {
        echo sprintf("Something went wrong when trying to get profiles: %s", htmlspecialchars($exception->getMessage()));
    }

    return $profiles;
}



function logout(): void {
    if (isset($_GET['logout'])) {

        switch ($_GET['logout']) {
            case Constants::LOGOUT_SUCCESS:
                unset($_SESSION);
                header('Location: ../user/login.php?clear=true', true, 303);
                break;
            default:
                echo 'Er ging iets fout!';
                break;
        }
    }
}

