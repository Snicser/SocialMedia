<!DOCTYPE HTML>
<html lang="nl">
<head>
    <title>FlappiesMedia</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/login.css">

</head>
<body>

    <header>
        <div class="text-center mb-4">
            <h1>FlappiesMedia</h1>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="background-color-container">
                <form class="form-signin m-auto w-100" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

                    <div class="form-label-group">
                        <input type="text" id="username" name="username" class="form-control" placeholder="Username" required="" autofocus="">
                        <label for="username">Username</label>
                    </div>

                    <div class="form-label-group">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required="">
                        <label for="password">Password</label>
                    </div>

                    <div class="checkbox mb-3">
                        <label>
                            <input type="checkbox" value="remember-me"> Onthoud mij
                        </label>
                    </div>

                    <button class="btn btn-lg btn-login b-0" type="submit" name="login" id="login">Inloggen</button>

                    <div class="my-3">
                        <a class="btn-go-back position-relative" href="../">← Ga naar Snoepwinkel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

</body>
</html>

