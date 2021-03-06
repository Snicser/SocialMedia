<?php

require_once ('../Utils/Constants.php');

if (isset($_GET['register'])) {

    // Maybe switch statement??
    echo match ($_GET['register']) {
        Constants::REGISTER_FAILED => 'Er ging iets fout!',
        Constants::REGISTER_FAILED_EMAIL => 'Geen geldig email!',
        Constants::REGISTER_FAILED_SERVER_ERROR => 'Er ging iets fout bij onze servers!',
        default => 'Er is een onbekende fout opgetrenden, neem contact op met de beheerder!',
    };
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

</head>
<body>


    <main>
        <section>
            <div class="container">

                <div class="row">
                    <div class="col">

                        <form action="process.php" method="POST" accept-charset="UTF-8">

                            <div class="row">
                                <div class="col">
                                    <label for="first-name">Voornaam</label>
                                    <input type="text" class="form-control" id="first-name" name="first-name" placeholder="First name">
                                </div>

                                <div class="col">
                                    <label for="last-name">Achternaam</label>
                                    <input type="text" class="form-control" id="last-name" name="last-name" placeholder="Last name">
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="email">Email adres</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
                            </div>

                            <div class="form-group">
                                <label for="password">Wachtwoord</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="name@example.com">
                            </div>

                            <div class="form-group">
                                <label for="birthday">Geboortedatum</label>
                                <input type="date" class="form-control" id="birthday" aria-describedby="birthday" name="birthday">
                            </div>

                            <section>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="men" value="men">
                                    <label class="form-check-label" for="men">
                                        Man
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="women" value="women">
                                    <label class="form-check-label" for="women">
                                        Vrouw
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="different" value="different">
                                    <label class="form-check-label" for="different">
                                        Anders
                                    </label>
                                </div>
                            </section>


                            <div>
                                <p>
                                    Door op Registreren te klikken, ga je akkoord met onze Voorwaarden. Meer informatie over hoe we je gegevens verzamelen, gebruiken en delen, vind je in ons Gegevensbeleid. Meer informatie over hoe we cookies en vergelijkbare technologie gebruiken, vind je in ons Cookiebeleid. We sturen je mogelijk sms-meldingen, waarvoor je je op elk moment kunt afmelden.
                                </p>
                            </div>

                            <div class="form-group">
                                <button type="submit" name="register-submit" class="btn btn-primary">Submit</button>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </section>
    </main>

</body>
</html>
