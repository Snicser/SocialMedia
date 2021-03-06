<?php

// Get the stuff we need
require_once 'inc/database.php';
require_once 'inc/functions.php';
require_once 'Utils/Constants.php';


// Check if the user is logged in
if ((!isset($_SESSION['logged-in-user'])) || (!isset($_SESSION['user_id'])) || empty($_SESSION['logged-in-user']) || empty($_SESSION['user_id'])) {
    header('Location: user/login.php', true, 303);
}

if (isset($_GET['logout'])) {

    switch ($_GET['logout']) {
        case Constants::LOGOUT_SUCCESS:
            unset($_SESSION['logged-in-user']);
            unset($_SESSION['user_id']);
            header('Location: user/login.php', true, 303);
            break;
        default:
            echo 'Er ging iets fout!';
            break;
    }
}

// TODO
// Code update, comments toevoegen, PHP Documentatie


// TODO:
// Bug -> Comment zie je onder elke post
// Bug -> Profile gevonden allen zegt die gelijk ook de message dat niet geovonden is terwijl die wel is gevonden
?>

<!DOCTYPE HTML>
<html lang="nl">
<head>
    <title>FlappiesMedia</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/modal.css">

    <!-- Font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous"/>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light navigation">
            <div class="container justify-content-center">
                <div class="d-flex flex-row justify-content-between align-items-center col-9">

                    <a class="navbar-brand" href="#">
                        <img src="images/instagram-logo" alt="Platform logo" loading="lazy">
                    </a>

                    <div>
                        <form class="form-inline my-2 my-lg-0 position-relative search" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="search-username">
                            <button type="submit" name="search-profile" class="position-absolute btn-search"><i class="fa fa-search"></i></button>
                        </form>

                        <?php

                            // Check connection
                            $connection = getDatabaseConnection();
                            if (!$connection) {
                                header($_SERVER["SERVER_PROTOCOL"], true, 503);
                                exit;
                            }

                            if (isset($_POST['search-profile'])) {

                                if (empty($_POST['search-username'])) {
                                    header('Location: '.  htmlspecialchars($_SERVER["PHP_SELF"]), true, 303);
                                    exit;
                                }

                                $searchUsername = $_POST['search-username'] ?? 'Undefined';

                                $profiles = searchProfiles($connection, $searchUsername);

                                if (empty($profile)) {
                                    echo 'Geen profiel gevonden!';
                                }

                                foreach ($profiles as $profile) {
                                    ?>
                                    <a href="user/profile.php?userid=<?= $profile['user_id'] ?>">Profiel van <?= $profile['username'] ?></a>
                                    <?php
                                }
                            }
                        ?>
                    </div>

                    <div class="d-flex flex-row">

                        <ul class="list-inline m-0">

                            <!-- Home -->
                            <li class="list-inline-item">
                                <a href="#" class="link-menu">
                                    <svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-house-door-fill"
                                         fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                                d="M6.5 10.995V14.5a.5.5 0 0 1-.5.5H2a.5.5 0 0 1-.5-.5v-7a.5.5 0 0 1 .146-.354l6-6a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 .146.354v7a.5.5 0 0 1-.5.5h-4a.5.5 0 0 1-.5-.5V11c0-.25-.25-.5-.5-.5H7c-.25 0-.5.25-.5.495z" />
                                        <path fill-rule="evenodd"
                                              d="M13 2.5V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z" />
                                    </svg>
                                </a>
                            </li>

                            <!-- Direct message -->
                            <li class="list-inline-item ml-2">
                                <a href="#" class="link-menu">
                                    <svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-inboxes"
                                         fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                              d="M.125 11.17A.5.5 0 0 1 .5 11H6a.5.5 0 0 1 .5.5 1.5 1.5 0 0 0 3 0 .5.5 0 0 1 .5-.5h5.5a.5.5 0 0 1 .496.562l-.39 3.124A1.5 1.5 0 0 1 14.117 16H1.883a1.5 1.5 0 0 1-1.489-1.314l-.39-3.124a.5.5 0 0 1 .121-.393zm.941.83l.32 2.562a.5.5 0 0 0 .497.438h12.234a.5.5 0 0 0 .496-.438l.32-2.562H10.45a2.5 2.5 0 0 1-4.9 0H1.066zM3.81.563A1.5 1.5 0 0 1 4.98 0h6.04a1.5 1.5 0 0 1 1.17.563l3.7 4.625a.5.5 0 0 1-.78.624l-3.7-4.624A.5.5 0 0 0 11.02 1H4.98a.5.5 0 0 0-.39.188L.89 5.812a.5.5 0 1 1-.78-.624L3.81.563z" />
                                        <path fill-rule="evenodd"
                                              d="M.125 5.17A.5.5 0 0 1 .5 5H6a.5.5 0 0 1 .5.5 1.5 1.5 0 0 0 3 0A.5.5 0 0 1 10 5h5.5a.5.5 0 0 1 .496.562l-.39 3.124A1.5 1.5 0 0 1 14.117 10H1.883A1.5 1.5 0 0 1 .394 8.686l-.39-3.124a.5.5 0 0 1 .121-.393zm.941.83l.32 2.562A.5.5 0 0 0 1.884 9h12.234a.5.5 0 0 0 .496-.438L14.933 6H10.45a2.5 2.5 0 0 1-4.9 0H1.066z" />
                                    </svg>
                                </a>
                            </li>

                            <!-- Notifications -->
                            <li class="list-inline-item ml-2">
                                <a href="#" class="link-menu">
                                    <svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-heart"
                                         fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                              d="M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z" />
                                    </svg>
                                </a>
                            </li>

                            <!-- Profile -->
                            <li class="list-inline-item ml-2 align-middle">
                                <a href="user/profile.php?userid=<?= $_SESSION['user_id'] ?>" class="link-menu">
                                    <div class="rounded-circle overflow-hidden d-flex justify-content-center align-items-center border navbar-profile-photo">
                                        <img src="images/avatar.png" alt="Avatar" style="transform: scale(1.5); width: 100%; position: absolute; left: 0;">
                                    </div>
                                </a>
                            </li>

                            <!-- Logout -->
                            <li class="list-inline-item ml-2">
                                <a href="index.php?logout=true" class="link-menu">
                                    Uitloggen
                                </a>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main>

        <!-- Pos fixed float left top 0 left 20px -->
        <section>
            <div class="sticky-post-upload position-fixed">
                <form action="user/upload.php" method="POST" enctype="multipart/form-data">
                    <h4>Select image to upload:</h4>
                    <input type="file" class="form-control mb-3 shadow-none border-none" name="user-file" id="fileToUpload" required="" style="border-color: #000">

                    <div class="form-floating mb-3">
                            <textarea class="form-control shadow-none" name="caption" id="floatingTextarea"
                                      placeholder="Schrijf een bijschrift..." maxlength="200"
                                      style="height: 100px;border-color: #000;resize:none;" required=""></textarea>
                        <label for="floatingTextarea">Schrijf een bijschrift...</label>
                    </div>

                    <button type="submit" name="post-message" class="btn btn-post-upload shadow-none">Post uploaden</button>
                </form>
            </div>
        </section>


        <section>

            <div class="container">

                <div class="row">

                    <div class="col">

                        <?php

                        $posts = getPostFromFollowingUsers($connection, $_SESSION['user_id']);

                        if (empty($posts)) {
                            echo 'No posts found!';
                        }


                        foreach ($posts as $post) {
                            ?>
                                <div class="pt-4">
                                    <article class="card mx-auto">
                                        <div class="card-header">
                                            <img src="images/avatar.png" alt="Avatar" class="avatar"><a href="user/profile.php?userid=<?= $post['user_id'] ?>"><strong><?= $post['username'] ?></strong></a>
                                        </div>

                                        <img src="user/image.php?image=<?= $post['image_path'] ?>&userid=<?= $post['user_id'] ?>&username=<?=  $post['username']?>" class="post-image" alt="Photo">

                                        <div class="card-body">

                                            <h6 class="card-title">
                                                <img src="images/avatar.png" alt="Avatar" class="avatar avatar-body"><span><small>Gelikte door <strong><?= $post['likes'] ?></strong> andere</small></span>
                                                <i class="far fa-heart"></i>
                                            </h6>

                                            <p class="card-text"><?= $post['caption'] ?></p>
                                            <p class="card-text"><small class="text-muted">Datum geupload:
                                                    <?php

                                                    // Calculate the upload date when the post was uploaded
                                                    // After the calculation we can display the difference
                                                    // So the users will know when the post was uploaded
                                                    $date = $post['upload_date'] ?? 'No date set';

                                                    try {
                                                        $origin = new DateTime($post['upload_date']);
                                                    } catch (Exception $e) {
                                                        echo sprintf("Something went wrong when trying to calculate the upload date: %s", htmlspecialchars($exception->getMessage()));
                                                    }

                                                    $target = new DateTime();
                                                    $interval = $origin -> diff($target);

                                                    // We need to format to a string to display it to the user
                                                    echo $interval -> format('%a dagen geleden');
                                                    ?></small>
                                            </p>
                                        </div>

                                        <div class="card-footer bg-transparent">
                                            <form action="user/process.php" method="POST">
                                                <button class="btn btn-primary" type="submit" name="add-comment">Add comment</button>
                                                <label for="place-comment-<?= $post['post_id']?>"><input type="text" id="place-comment-<?= $post['post_id']?>" name="comment"></label>
                                                <input type="hidden" name="post-id" value="<?= $post['post_id'] ?>">
                                            </form>

                                            <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                                                <input type="hidden" name="post-id" value="<?= $post['post_id'] ?>">
                                                <input type="submit" name="check-comments" value="Zien">
                                            </form>

                                            <?php

                                            // Check if submit button is clicked for login
                                            if (isset($_POST['check-comments'])) {

                                                $postId = $_POST['post-id'] ?? 0;


                                                $comments = getCommentsFromPost($connection, $postId);

                                                if (empty($comments)) {
                                                    header("Location: ../index.php?commentsee=failed");
                                                    exit;
                                                }


                                                foreach ($comments as $comment) {
                                                    $userName = $comment['username'];
                                                    $comment = $comment['comment'];

                                                    echo '<div>Gebruiker: '.  $userName . ' zegt: ' . $comment . '</div>';
                                                }

                                            }

                                            ?>

                                        </div>
                                    </article>
                                </div>
                                <?php
                        }

                        ?>

                    </div>
                </div>

            </div>
        </section>
    </main>

</body>
</html>
