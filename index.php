<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soundscape</title>
    <link rel="stylesheet" href="./static/css/index.css" />
</head>

<body>
    <header class="header-section">
        <div>
            <img src="./static/images/soundscape_logo.png" alt="soundscape-small-logo" class="soundscape-small-logo">
        </div>
        <div>
            <button class="button"><a href="login_form.php">Sign in</a></button>
            <button class="button"><a href="registration_form.php">Sign up</a></button>
        </div>
    </header>
    <section class="main-section">
        <div>
            <img src="./static/images/soundscape_logo.png" alt="tune-stats-logo" class="tune-stats-logo">
        </div>

        <div class="middle">
            <div class="slogan">
                <span>
                    <strong>GET TO KNOW YOUR STORY</strong>
                    <br>
                    LISTEN YOUR FAVOURITE MUSIC
                    <br>
                    VIEW YOUR STATS
                    <br>
                    <span class="small-text">Discover your music DNA</span>
                </span>
            </div>

            <div>
                <img src="./static/images/spotify_on_device.png" alt="spotify-on-device" class="spotify-image">
            </div>
        </div>
    </section>
    <footer>

    </footer>
</body>

</html>