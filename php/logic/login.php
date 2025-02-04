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
    <link rel="stylesheet" href="../node_modules/uikit/dist/css/uikit.min.css" />
    <title>Halaman Login</title>
</head>
<body>

    <!-- header -->
    <?php include 'header.php'; ?>
    <!-- end header -->

    <!-- body -->
    <div class="uk-container uk-text-center">  
        <h3 class="uk-heading-line"><span>Halaman Login</span></h3>
        <form action="" method="post">
            <div class="uk-margin">
                <label><input class="uk-radio" name="akun" value="admin" type="radio"/><span>Admin</span></label>
                <label><input class="uk-radio" name="akun" value="agen" type="radio"/><span>Agen</span></label>
                <label><input class="uk-radio" name="akun" value="pelanggan" type="radio"/><span>Pelanggan</span></label>
            </div>
            <div class="uk-margin">
                <input class="uk-input" type="text" id="email" name="email" placeholder="Email">
            </div>
            <div class="uk-margin">
                <input class="uk-input" type="password" id="password" name="password" placeholder="Password">
            </div>
            <div class="uk-margin">
                <button class="uk-button uk-button-primary" type="submit" name="login">Login</button>
            </div>
        </form>
    </div>
    <!-- end body -->

    <!-- footer -->
    <?php include "footer.php"; ?>
    <!-- end footer -->

    <script src="../node_modules/uikit/dist/js/uikit.min.js"></script>
    <script src="../node_modules/uikit/dist/js/uikit-icons.min.js"></script>
</body>
</html>

<?php
if (isset($_POST["login"])) {
    $akun = $_POST["akun"] ?? '';
    if ($akun == 'agen') {
        $email = htmlspecialchars($_POST["email"]);
        $password = htmlspecialchars($_POST["password"]);
        validasiEmail($email);
        $stmt = $connect->prepare("SELECT * FROM agen WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row["password"])) {
                $_SESSION["agen"] = $row["id_agen"];
                $_SESSION["login-agen"] = true;
                echo "<script>document.location.href = 'index.php';</script>";
                exit;
            } else {
                echo "<script>Swal.fire('Gagal Login','Password Salah','warning');</script>";
            }
        } else {
            echo "<script>Swal.fire('Gagal Login','Email Tidak Terdaftar','warning');</script>";
        }
    } elseif ($akun == 'pelanggan') {
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);
        validasiEmail($email);
        $stmt = $connect->prepare("SELECT * FROM pelanggan WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $data = $result->fetch_assoc();
            if (password_verify($password, $data["password"])) {
                $_SESSION["pelanggan"] = $data["id_pelanggan"];
                $_SESSION["login-pelanggan"] = true;
                echo "<script>document.location.href = 'index.php';</script>";
                exit;
            } else {
                echo "<script>Swal.fire('Gagal Login','Password Salah','warning');</script>";
            }
        } else {
            echo "<script>Swal.fire('Gagal Login','Email Tidak Terdaftar','warning');</script>";
        }
    } elseif ($akun == 'admin') {
        $username = htmlspecialchars($_POST["email"]);
        $password = htmlspecialchars($_POST["password"]);
        validasiUsername($username);
        $stmt = $connect->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $data = $result->fetch_assoc();
            if (password_verify($password, $data["password"])) {
                $_SESSION["login-admin"] = true;
                $_SESSION["admin"] = $data["id_admin"];
                echo "<script>document.location.href = 'index.php';</script>";
            } else {
                echo "<script>Swal.fire('Gagal Login','Password Salah','warning');</script>";
            }
        } else {
            echo "<script>Swal.fire('Gagal Login','Username Tidak Terdaftar','warning');</script>";
        }
    } else {
        echo "<script>Swal.fire('Gagal Login','Pilih Jenis Akun Terlebih Dahulu','warning');</script>";
    }
}
?>