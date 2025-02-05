<?php
session_start();
include 'connect-db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../node_modules/uikit/dist/css/uikit.min.css" />
    <title>Team LaundryKu</title>
</head>
<body>
    <?php include "header.php"; ?>
    <div class="uk-container">
        <h3 class="uk-heading-line uk-text-center"><span>Team LaundryKu</span></h3>
        <div class="uk-grid-match uk-child-width-1-4@m" uk-grid>
            <div>
                <div class="uk-card uk-card-default uk-card-hover uk-card-body uk-animation-scale-up">
                    <div class="uk-card-media-top uk-text-center">
                        <img src="img/logo.png" alt="Nadya Oktaviana" class="uk-border-circle" width="150" height="150">
                    </div>
                    <h3 class="uk-card-title uk-text-center">Nadya Oktaviana</h3>
                    <p>I am a very simple card. I am good at containing small bits of information. I am convenient because I require little markup to use effectively.</p>
                    <div class="uk-card-footer">
                        <a href="#" class="uk-button uk-button-text">This is a link</a>
                    </div>
                </div>
            </div>
            <div>
                <div class="uk-card uk-card-default uk-card-hover uk-card-body uk-animation-scale-up">
                    <div class="uk-card-media-top uk-text-center">
                        <img src="img/logo.png" alt="Eka Nadya" class="uk-border-circle" width="150" height="150">
                    </div>
                    <h3 class="uk-card-title uk-text-center">Eka Nadya</h3>
                    <p>I am a very simple card. I am good at containing small bits of information. I am convenient because I require little markup to use effectively.</p>
                    <div class="uk-card-footer">
                        <a href="#" class="uk-button uk-button-text">This is a link</a>
                    </div>
                </div>
            </div>
            <div>
                <div class="uk-card uk-card-default uk-card-hover uk-card-body uk-animation-scale-up">
                    <div class="uk-card-media-top uk-text-center">
                        <img src="img/logo.png" alt="Hairul Lana" class="uk-border-circle" width="150" height="150">
                    </div>
                    <h3 class="uk-card-title uk-text-center">Hairul Lana</h3>
                    <p>I am a very simple card. I am good at containing small bits of information. I am convenient because I require little markup to use effectively.</p>
                    <div class="uk-card-footer">
                        <a href="#" class="uk-button uk-button-text">This is a link</a>
                    </div>
                </div>
            </div>
            <div>
                <div class="uk-card uk-card-default uk-card-hover uk-card-body uk-animation-scale-up">
                    <div class="uk-card-media-top uk-text-center">
                        <img src="img/logo.png" alt="Wina Artha" class="uk-border-circle" width="150" height="150">
                    </div>
                    <h3 class="uk-card-title uk-text-center">Wina Artha</h3>
                    <p>I am a very simple card. I am good at containing small bits of information. I am convenient because I require little markup to use effectively.</p>
                    <div class="uk-card-footer">
                        <a href="#" class="uk-button uk-button-text">This is a link</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "footer.php"; ?>
    <script src="../node_modules/uikit/dist/js/uikit.min.js"></script>
    <script src="../node_modules/uikit/dist/js/uikit-icons.min.js"></script>
</body>
</html>