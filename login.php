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
                        <label><input name="akun" value="admin" type="radio"/><span>Admin</span> </label>
                        <label><input name="akun" value="agen" type="radio"/><span>Agen</span> </label>
                        <label><input name="akun" value="pelanggan" type="radio"/><span>Pelanggan</span></label>
                    </li>
                    <li>
                        <input type="text" id="email" name="email" placeholder="Email">
                    </li>
                    <li>
                        <input type="password" id="password" name="password" placeholder="Password">
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

    $akun = $_POST["akun"] ?? '';

    if ($akun == 'agen') {
        $email = htmlspecialchars($_POST["email"]);
        $password = htmlspecialchars($_POST["password"]);
        validasiEmail($email);
        $result = mysqli_query($connect, "SELECT * FROM agen WHERE email = '$email'");
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
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

    } elseif ($akun == 'admin') { // <-- TIDAK ADA '}' SEBELUMNYA
        $username = htmlspecialchars($_POST["email"]);
        $password = htmlspecialchars($_POST["password"]);
        validasiUsername($username);
        $data = mysqli_query($connect, "SELECT * FROM admin WHERE username = '$username'");
        if (mysqli_num_rows($data) === 1) {
            $data = mysqli_fetch_assoc($data);
            $idAdmin = $data["id_admin"];
            if ($password === $data["password"]) {
                $_SESSION["login-admin"] = true;
                $_SESSION["admin"] = $idAdmin;
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
