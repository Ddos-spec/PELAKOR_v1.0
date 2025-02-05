<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PELAKOR</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.14/css/uikit.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.14/js/uikit.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.14/js/uikit-icons.min.js"></script>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #1e87f0; /* Blue color */
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px;
            font-size: 1.2em;
        }
        .navbar a:hover {
            background-color: #0d6efd;
            border-radius: 5px;
        }
        .navbar .logo {
            font-weight: bold;
            font-size: 1.5em;
        }
        .search-container {
            display: flex;
            align-items: center;
        }
        .search-container input {
            padding: 5px;
            border: none;
            border-radius: 5px;
        }
        .search-container button {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            background-color: white;
            color: #1e87f0; /* Blue color */
            cursor: pointer;
        }
    </style>
</head>
<body>

<header>
    <nav class="uk-container uk-navbar">
        <div class="uk-navbar-left">
            <ul class="uk-navbar-nav">
                <li class="uk-active">
                    <a href="#">UIKit<strong>ResMenu</strong></a>
                </li>
            </ul>
        </div>
        <div class="uk-navbar-right">
            <ul class="uk-navbar-nav uk-visible@s">
                <li><a class="uk-text-large" href="https://shubhamjain.co/about/">home</a></li>
                <li><a class="uk-text-large" href="https://shubhamjain.co/">registration</a></li>
                <li><a class="uk-text-large" href="https://shubhamjain.co/">login</a></li>
            </ul>
            <a href="#" class="uk-navbar-toggle uk-hidden@s" uk-navbar-toggle-icon uk-toggle="target: #sidenav"></a>
        </div>
    </nav>
</header>

<div id="sidenav" uk-offcanvas="flip: true" class="uk-offcanvas">
    <div class="uk-offcanvas-bar">
        <ul class="uk-nav">
            <li><a class="uk-text-large" href="https://shubhamjain.co/about/">about</a></li>
            <li><a class="uk-text-large" href="https://shubhamjain.co/">blog</a></li>
        </ul>
    </div>
</div>

<nav class="navbar">
    <div class="logo">PELAKOR</div>
    <div>
        <a href="index.php">Home</a>
        <div class="search-container">
            <input type="text" placeholder="Search...">
            <button type="button">Search</button>
        </div>
        <?php
            global $connect;

            if (isset($_SESSION["login-pelanggan"]) && isset($_SESSION["pelanggan"])) {
                $idPelanggan = $_SESSION["pelanggan"];
                $data = mysqli_query($connect, "SELECT * FROM pelanggan WHERE id_pelanggan = '$idPelanggan'");
                $data = mysqli_fetch_assoc($data);
                $nama = $data["nama"];
                echo "<a href='pelanggan.php'>$nama (Pelanggan)</a>";
            } else if (isset($_SESSION["login-agen"]) && isset($_SESSION["agen"])) {
                $id_agen = $_SESSION["agen"];
                $data = mysqli_query($connect, "SELECT * FROM agen WHERE id_agen = '$id_agen'");
                $data = mysqli_fetch_assoc($data);
                $nama = $data["nama_laundry"];
                echo "<a href='agen.php'>$nama (Agen)</a>";
            } else if (isset($_SESSION["login-admin"]) && isset($_SESSION["admin"])) {
                echo "<a href='admin.php'>Admin (Admin)</a>";
            }
        ?>
    </div>
</nav>

</body>
</html>
