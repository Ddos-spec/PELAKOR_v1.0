<?php
session_start();
include 'connect-db.php';
include 'functions/functions.php';
cekLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../node_modules/uikit/dist/css/uikit.min.css" />
    <title>Registrasi</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <h3 class="uk-heading-line uk-text-center"><span>Halaman Registrasi</span></h3>
    <br>
    <div class="uk-container uk-text-center">
        <a id="download-button" class="uk-button uk-button-primary uk-button-large" href="registrasi-pelanggan.php">Registrasi Pelanggan</a>
        <a id="download-button" class="uk-button uk-button-danger uk-button-large" href="registrasi-agen.php">Registrasi Agen</a>
    </div>
<?php include 'footer.php'; ?>
    <script src="../node_modules/uikit/dist/js/uikit.min.js"></script>
    <script src="../node_modules/uikit/dist/js/uikit-icons.min.js"></script>
</body>
</html>