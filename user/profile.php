<?php

require_once ('../inc/database.php');
require_once ('../inc/functions.php');


// Check if the user is logged in
if ((!isset($_SESSION['logged-in-user'])) || (!isset($_SESSION['user_id'])) || empty($_SESSION['logged-in-user']) || empty($_SESSION['user_id'])) {
    header('Location: user/login.php', true, 303);
    exit; // Make sure the code stops here
}

// Check connection
$connection = getDatabaseConnection();
if (!$connection) {
    header($_SERVER["SERVER_PROTOCOL"], true, 503);
    exit;
}

// Get the username and check if exits
$userName = $_SESSION['username'] ?? "Undefined";
$userId = $_SESSION['user_id'] ?? 0;
if (empty($userName) || empty($userId)) {
    header('Location: login.php?username=noname', true, 303);
    exit; // Make sure the code stops here
}

// Check if user id is set in URL bar
if (isset($_GET['userid'])) {

    // Get the user id from the URL bar
    $URL_USER_ID = $_GET['userid'] ?? 0;

    // Get profile name and also set into the session
    $profileName = getProfileName($connection, $URL_USER_ID);
    if (empty($profileName)) die("No profile found!");

    $userName = $profileName['username'];
    $_SESSION['username'] = $userName;
}


?>

<!DOCTYPE HTML>
<html lang="nl">
<head>
    <title>FlappiesMedia</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/profile.css">

</head>
<body>

<header>

    <a class="btn btn-primary" href="../index.php" role="button" style="font-size: 14px">Ga terug</a>



    <div class="container">

        <div class="profile">

            <div class="profile-image">

                <img src="https://images.unsplash.com/photo-1513721032312-6a18a42c8763?w=152&h=152&fit=crop&crop=faces" alt="">

            </div>


            <div class="profile-user-settings">
                <h1 class="profile-user-name"><?= $userName ?></h1>

                <?php

                $URL_USER_ID = $_GET['userid'] ?? 0;

                if ($URL_USER_ID != $userId) {
                    $yourProfile = false;
                } else {
                    $yourProfile = true;
                }

                if ($yourProfile) {
                    echo '<a href="settings.php?userid=' . $URL_USER_ID . '" class="btn profile-edit-btn">Edit Profile</a>';
                    echo '<button class="btn profile-settings-btn" aria-label="profile settings"><i class="fas fa-cog"></i></button>';
                } else {
                    ?><a href="follow.php?userid=<?= $URL_USER_ID ?>" class="btn profile-edit-btn">Volgen</a><?php
                }

                ?>


            </div>

            <div class="profile-stats">

                <ul>
                    <li><span class="profile-stat-count"><?= $postUploadedCount = getPostUploadedCount($connection, $URL_USER_ID); ?></span> posts</li>
                    <li><span class="profile-stat-count"><?= $followersCount = getFollowersCount($connection, $URL_USER_ID); ?></span> followers</li>
                    <li><span class="profile-stat-count"><?= $followingCount = getFollowingCount($connection, $URL_USER_ID); ?></span> following</li>
                </ul>

            </div>

            <div class="profile-bio">
                <p><span class="profile-real-name"><?= $userName ?></span> Lorem ipsum dolor sit, amet consectetur adipisicing elit ?????????????????</p>
            </div>
        </div>
    </div>


</header>

<main>

    <div class="container">

        <div class="gallery">

            <!-- Load the posts -->
            <?php

            // Check connection
            $connection = getDatabaseConnection();
            if (!$connection) {
                header($_SERVER["SERVER_PROTOCOL"], true, 503);
                exit;
            }

            $posts = getProfilePostsAndName($connection, $URL_USER_ID);

            if (empty($posts)) {
                ?>
                <div class="alert alert-warning no-posts-found" role="alert">
                    Geen posts gevonden!
                </div>
                <?php
            }



            foreach ($posts as $post) {
                ?>
                <div class="gallery-item" tabindex="0">
                    <img src="image.php?image=<?= $post['image_path'] ?>&userid=<?= $URL_USER_ID ?>&username=<?=  $post['username']?>"  class="gallery-image" alt="Photo">

                    <div class="gallery-item-info">
                        <ul>
                            <li class="gallery-item-likes"><span class="visually-hidden">Likes:</span><i class="fas fa-heart" aria-hidden="true"></i><?= $post['likes'] ?></li>
                            <li class="gallery-item-comments"><span class="visually-hidden">Comments:</span><i class="fas fa-comment" aria-hidden="true"></i><?= $post['comments_total'] ?></li>
                        </ul>
                    </div>
                </div>
                <?php
            }

            ?>

        </div>
    </div>


</main>

</body>
</html>
