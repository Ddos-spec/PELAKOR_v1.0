<?php

session_start();
include 'connect-db.php';
include 'functions/functions.php';

// Sesuaikan dengan jenis login
if ((isset($_SESSION["login-admin"]) && isset($_SESSION["admin"]))) {
    $login = "Admin";
    $idAdmin = $_SESSION["admin"];
} elseif ((isset($_SESSION["login-agen"]) && isset($_SESSION["agen"]))) {
    $idAgen = $_SESSION["agen"];
    $login = "Agen";
} elseif ((isset($_SESSION["login-pelanggan"]) && isset($_SESSION["pelanggan"]))) {
    $idPelanggan = $_SESSION["pelanggan"];
    $login = "Pelanggan";
} else {
    echo "<script>document.location.href = 'index.php';</script>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "headtags.html"; ?>
    <script src="js/script-debug-v4.js"></script>
    <title>Ganti Kata Sandi</title>
</head>
<body> 
    <?php include 'header.php'; ?>
    <h3 class="header col s24 light center">Ganti Kata Sandi</h3>
    <form action="" method="POST" class="col s18 center"> 
        <div class="input-field inline">
            <input type="password" name="passwordLama" placeholder="Password Lama">
            <input type="password" name="password" placeholder="Password Baru">
            <input type="password" name="repassword" placeholder="Konfirmasi Password Baru">
            <button class="waves-effect blue darken-2 btn" type="submit" name="gantiPassword">Ganti Password</button>
        </div>
    </form>
    <br>
    <?php include "footer.php"; ?>
</body>
</html>

<?php

// Proses ubah sandi
if (isset($_POST["gantiPassword"])) {
    $passwordLama = htmlspecialchars($_POST["passwordLama"]);
    $passwordBaru = htmlspecialchars($_POST["password"]);
    $repassword = htmlspecialchars($_POST["repassword"]);

    if ($login == 'Admin') {
        $data = mysqli_query($connect, "SELECT * FROM admin WHERE id_admin = $idAdmin");
        $data = mysqli_fetch_assoc($data);

        // Gunakan password_verify jika password disimpan dengan hash
        if (!password_verify($passwordLama, $data["password"])) {
            echo "<script>Swal.fire('Password Lama Salah','','error');</script>";
            exit;
        }

    } elseif ($login == "Agen") {
        $data = mysqli_query($connect, "SELECT * FROM agen WHERE id_agen = $idAgen");
        $data = mysqli_fetch_assoc($data);

        if (!password_verify($passwordLama, $data["password"])) {
            echo "<script>Swal.fire('Password Lama Salah','','error');</script>";
            exit;
        }

    } elseif ($login == "Pelanggan") {
        $data = mysqli_query($connect, "SELECT * FROM pelanggan WHERE id_pelanggan = $idPelanggan");
        $data = mysqli_fetch_assoc($data);

        if (!password_verify($passwordLama, $data["password"])) {
            echo "<script>Swal.fire('Password Lama Salah','','error');</script>";
            exit;
        }
    }

    // Validasi password baru dan konfirmasi
    if ($passwordBaru !== $repassword) {
        echo "<script>Swal.fire('Password Baru Tidak Sama','','error');</script>";
        exit;
    }

    // Hash password baru sebelum menyimpan
    $hashedPassword = password_hash($passwordBaru, PASSWORD_DEFAULT);

    // Update password sesuai role
    if ($login == 'Admin') {
        $query = mysqli_query($connect, "UPDATE admin SET password = '$hashedPassword' WHERE id_admin = $idAdmin");
    } elseif ($login == "Agen") {
        $query = mysqli_query($connect, "UPDATE agen SET password = '$hashedPassword' WHERE id_agen = $idAgen");
    } elseif ($login == "Pelanggan") {
        $query = mysqli_query($connect, "UPDATE pelanggan SET password = '$hashedPassword' WHERE id_pelanggan = $idPelanggan");
    }

    // Cek apakah password berhasil diubah
    if (mysqli_affected_rows($connect) > 0) {
        echo "<script>Swal.fire('Password Berhasil Diganti','','success');</script>";
    }
}

?>
