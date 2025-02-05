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
        .uk-navbar-nav button {
            padding: 10px 15px; /* Increased padding */
            font-size: 1.1em; /* Increased font size */
            color: white; /* Ensure text color is white */
            background-color: transparent; /* Transparent background */
            border: none; /* No border */
            cursor: pointer; /* Pointer cursor */
        }
        .uk-navbar-nav button:hover {
            background-color: #0d6efd; /* Hover effect */
            border-radius: 5px;
        }
    </style>
</head>
<body>

<header>
    <nav class="uk-container uk-navbar uk-navbar-container" style="background-color: #1e87f0;">
        <div class="uk-navbar-left">
            <ul class="uk-navbar-nav">
                <li class="uk-active">
                    <a href="#">PELA<strong>KOR</strong></a>
                </li>
            </ul>
        </div>
        <div class="uk-navbar-right">
            <ul class="uk-navbar-nav">
                <li>
                    <button onclick="location.href='index.php'"><span uk-icon="icon: home"></span> Home</button>
                </li>
                <li>
                    <button onclick="location.href='registrasi.php'"><span uk-icon="icon: user"></span> Registration</button>
                </li>
                <li>
<button onclick="location.href='logic/login.php'"><span uk-icon="icon: sign-in"></span> Login</button>
                </li>
            </ul>
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

</body>
</html>
