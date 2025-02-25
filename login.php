<?php
session_start();
include 'connect-db.php';
include 'functions/functions.php';

// validasi login
cekLogin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "headtags.html"; ?>
    <title>Halaman Login</title>
</head>
<body>

    <!-- header -->
    <?php include 'header.php'; ?>
    <!-- end header -->

    <!-- body -->
    <div class="container center">  
        <h3 class="header light center">Halaman Login</h3>
        <form action="" method="post">
            <div class="input-field inline">
                <ul>
                    <li>
                        <input type="hidden" name="akun" value="user" />
                    </li>
                    <li>
                        <!-- Ketik "admin" jika ingin login sebagai admin -->
                        <input type="text" id="email" name="email" placeholder="Email atau Username (admin)" required>
                    </li>
                    <li>
                        <input type="password" id="password" name="password" placeholder="Password" required>
                        <p class="tes"></p>
                    </li>
                    <br>
                    <li>
                        <div class="center">
                            <button class="waves-effect blue darken-2 btn" type="submit" name="login">Login</button>
                        </div>
                    </li>
                </ul>
            </div>
        </form>
    </div>
    <!-- end body -->

    <!-- footer -->
    <?php include "footer.php"; ?>
    <!-- end footer -->

</body>
</html>

<?php
if (isset($_POST["login"])) {
    $userInput = htmlspecialchars($_POST["email"]);
    $password  = htmlspecialchars($_POST["password"]);

    // Jika bukan admin, lakukan validasi format email
    if ($userInput !== 'admin') {
        validasiEmail($userInput);
    }

    // Proses login admin: bypass validasi email dan gunakan username 'admin'
    if ($userInput === 'admin') {
        $adminQuery = mysqli_query($connect, "SELECT * FROM admin WHERE username = 'admin'");
        if (mysqli_num_rows($adminQuery) === 1) {
            $adminData = mysqli_fetch_assoc($adminQuery);
            // Cek password langsung (pastikan format penyimpanan password sesuai)
            if ($password === $adminData["password"]) {
                $_SESSION["login-admin"] = true;
                $_SESSION["admin"] = $adminData["id_admin"];
                echo "<script>document.location.href = 'index.php';</script>";
                exit;
            }
        }
        // Jika login admin gagal
        echo "<script>Swal.fire('Gagal Login','Username atau Password Salah','warning');</script>";
        exit;
    }

    // Proses login untuk agen (berdasarkan email)
    $result = mysqli_query($connect, "SELECT * FROM agen WHERE email = '$userInput'");
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row["password"])) {
            $_SESSION["agen"] = $row["id_agen"];
            $_SESSION["login-agen"] = true;
            echo "<script>document.location.href = 'index.php';</script>";
            exit;
        }
    }

    // Proses login untuk pelanggan (berdasarkan email)
    $result = mysqli_query($connect, "SELECT * FROM pelanggan WHERE email = '$userInput'");
    if (mysqli_num_rows($result) === 1) {
        $data = mysqli_fetch_assoc($result);
        if (password_verify($password, $data["password"])) {
            $_SESSION["pelanggan"] = $data["id_pelanggan"];
            $_SESSION["login-pelanggan"] = true;
            echo "<script>document.location.href = 'index.php';</script>";
            exit;
        }
    }

    // Jika tidak ditemukan kecocokan
    echo "<script>Swal.fire('Gagal Login','Email atau Password Salah','warning');</script>";
}
?>
