<?php

// mulai session
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
    <!-- header -->
    <?php include 'header.php'; ?>
    <!-- end header -->

    <h3 class="uk-heading-line uk-text-center"><span>Halaman Registrasi</span></h3>
    <br>

    <!-- body -->
    <div class="uk-container uk-text-center">
        <a id="download-button" class="uk-button uk-button-primary uk-button-large" href="registr