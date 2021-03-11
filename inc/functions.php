<?php

use JetBrains\PhpStorm\Pure;

/**
 * Adds an user to the database after registration
 *
 * @param $connection
 * @param $table
 * @param $first_name
 * @param $last_name
 * @param $email
 * @param $password
 * @param $birthday
 * @param $gender
 */
function addUser($connection, $table, $first_name, $last_name, $email, $password, $birthday, $gender) {
    $query = "INSERT INTO `$table` (`first_name`, `last_name`, `email`, `password`, `birthday`, `gender`, `username`, `is_admin`, `profile_id`) 
                VALUES (:first_name, :last_name, :email, :password, :birthday, :gender, :username, :is_admin, ?)";

    $preparedStatement = $connection -> prepare($query);
    $preparedStatement -> bindParam(':first_name', $first_name, PDO::PARAM_STR, 30);
    $preparedStatement -> bindParam(':last_name', $last_name, PDO::PARAM_STR, 30);
    $preparedStatement -> bindParam(':email', $email, PDO::PARAM_STR, 200);
    $preparedStatement -> bindParam(':password', $password, PDO::PARAM_STR, 250);
    $preparedStatement -> bindParam(':birthday', $birthday);
    $preparedStatement -> bindParam(':gender', $gender, PDO::PARAM_STR, 15);
    $preparedStatement -> bindParam(':username', $first_name, PDO::PARAM_STR, 30);
    $preparedStatement -> bindParam(':is_admin', 0, PDO::PARAM_INT);

    // TODO: Figure out profile_id
    // TODO: Document this function
    $preparedStatement -> execute();
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
    } else {
        return false;
    }
}
