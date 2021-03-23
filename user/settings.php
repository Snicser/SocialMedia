<?php

require_once '../inc/functions.php';
require_once '../inc/database.php';

// Check if the user is logged in
if ((!isset($_SESSION['logged-in-user'])) || (!isset($_SESSION['user_id'])) || empty($_SESSION['logged-in-user']) || empty($_SESSION['user_id'])) {
    header('Location: user/login.php', true, 303);
}

// Check connection
$connection = getDatabaseConnection();
if (!$connection) {
    header($_SERVER["SERVER_PROTOCOL"], true, 503);
    exit;
}

logout();

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
    <link rel="stylesheet" type="text/css" href="../css/settings.css">

</head>
<body>

    <a href="../index.php">Go back homepage</a>

    <main>
        <section>
            <div class="container">
                <h3 class="text-center pt-3 pb-3 welcome-text">Settings</h3>
                <h5 class="text-center pb-3 welcome-text-secondary">Uiterlijk</h5>
                <div class="space-line-horizontal line"></div>

                <!-- Appearance info -->
                <div class="d-flex flex-row">
                    <div class="p-2 user-information-title">
                        <div class="d-flex flex-column">
                            <div class="p-2">Darkmode:</div>
                        </div>
                    </div>
                    <div class="p-2 user-information">
                        <div class="d-flex flex-column">
                            <div class="switch-box">
                                <label class="switch">
                                    <input
                                        class="slider-darkmode"
                                        onclick="app.changeDarkmode()"
                                        type="checkbox"
                                        checked
                                    />
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- User info -->
                <h5 class="text-center pb-3 pt-3 welcome-text-secondary">Gebruikerinformatie</h5>
                <div class="space-line-horizontal line"></div>
                <div class="d-flex flex-row">
                    <div class="p-2">
                        <div class="d-flex flex-column">

                            <?php

                            $URL_USER_ID = $_GET['userid'] ?? 0;
                            $userDetails = getUserInformation($connection, $URL_USER_ID);

                            ?>

                            <div class="pl-2 pt-2 user-information-title">Voornaam</div>
                            <div class="pl-2 user-information">
                                <span class="user-firstname"><?= $userDetails['first_name'] ?></span>
                            </div>
                            <div class="pl-2 pt-2 user-information-title">Achternaam</div>
                            <div class="pl-2 user-information">
                                <span class="user-surname"><?= $userDetails['last_name'] ?></span>
                            </div>
                            <div class="pl-2 pt-2 user-information-title">E-mail</div>
                            <div class="pl-2 user-information">
                                <span class="user-email"><?= $userDetails['email'] ?></span>
                            </div>

                            <form action="updateProfile.php?userid=<?= $URL_USER_ID ?>" method="POST">
                                <label for="update-email">Nieuw email adres:</label>
                                <input type="email" name="update-email" id="update-email" required="">
                                <button name="update-user-profile" id="update-user-profile">Verander</button>
                            </form>
                        </div>


                    </div>
                    <div class="p-2 user-information">
                        <div class="d-flex flex-column"></div>
                    </div>
                </div>

            </div>
        </section>
    </main>

    <footer>
        <a href="settings.php?logout=true" class="logout my-2">
            Uitloggen
        </a>
    </footer>
</body>
</html>
