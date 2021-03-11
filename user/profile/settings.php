<!DOCTYPE HTML>
<html lang="nl">
<head>
    <title>FlappiesMedia</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../css/style.css">
    <link rel="stylesheet" type="text/css" href="../../css/settings.css">

</head>
<body>

    <!-- Header include -->

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

                <!-- Color picker -->
                <div class="d-flex flex-row">
                    <div class="p-2 user-information-title">
                        <div class="d-flex flex-column">
                            <div class="p-2">Style color:</div>
                        </div>
                    </div>
                    <div class="p-2 user-information">
                        <div class="d-flex flex-column">
                            <div>
                                <input
                                    class="color-picker"
                                    onchange="user.changeColor()"
                                    type="color"
                                    id="head"
                                    name="head"
                                    value="#e66465"
                                />
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
                            <div class="pl-2 pt-2 user-information-title">Voornaam</div>
                            <div class="pl-2 user-information">
                                <span class="user-firstname">Reinout</span>
                            </div>
                            <div class="pl-2 pt-2 user-information-title">Achternaam</div>
                            <div class="pl-2 user-information">
                                <span class="user-surname">Wijnholds</span>
                            </div>
                            <div class="pl-2 pt-2 user-information-title">E-mail</div>
                            <div class="pl-2 user-information">
                                <span class="user-email">ReinoutWijnholds2002@gmail.com</span>
                            </div>
                            <div class="pl-2 pt-2 user-information-title">Country</div>
                            <div class="pl-2 user-information">
                                <span class="user-country">--</span>
                            </div>

                            <div class="pl-2 pt-2 user-information-title">Wachtwoord</div>
                            <div class="pl-2 user-information">
                                <span class="user-country">Verander</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-2 user-information">
                        <div class="d-flex flex-column"></div>
                    </div>
                </div>

                <!-- User stats -->
                <h5 class="text-center pb-3 pt-3 welcome-text-secondary">
                    Gebruikers statistieken
                </h5>
                <div class="space-line-horizontal line"></div>
                <div class="d-flex flex-row">
                    <div class="p-2">
                        <div class="d-flex flex-column">
                            <div class="pl-2 pt-2 user-information-title">Statistieken</div>
                            <div
                                onclick="app.loadPage('statistics.html')"
                                class="pl-2 user-information"
                            >
                                <span class="user-firstname">Laad de statistieken zien</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-2 user-information">
                        <div class="d-flex flex-column"></div>
                    </div>
                </div>


                <!-- Login INFO -->
                <footer>
                    <p class="logout my-2" onclick="app.logout()">Uitloggen</p>
                </footer>
            </div>
        </section>
    </main>
</body>
</html>
