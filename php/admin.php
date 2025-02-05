<?php

session_start();
include 'connect-db.php';
include 'functions/functions.php';

// Cek jika bukan admin
cekAdmin();

// Ambil data admin
$idAdmin = $_SESSION["admin"];
$data = mysqli_query($connect, "SELECT * FROM admin WHERE id_admin = '$idAdmin'");
$admin = mysqli_fetch_assoc($data);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'headtags.html'; ?>
    <title>Profil Admin</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="uk-container uk-margin-top">
        <h3 class="uk-heading-line uk-text-center"><span>Profil Admin</span></h3>
        <form class="uk-form-stacked" action="" method="post">
            <div class="uk-margin">
                <label class="uk-form-label" for="username">Username</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="username" type="text" name="username" value="<?= $admin['username'] ?>">
                </div>
            </div>
            <div class="uk-margin">
                <button type="submit" class="uk-button uk-button-primary" name="simpan">Simpan Data</button>
                <a class="uk-button uk-button-danger" href="ganti-kata-sandi.php">Ganti Kata Sandi</a>
            </div>
        </form>
    </div>
    <?php include 'footer.php'; ?>
    <script src="../node_modules/uikit/dist/js/uikit.min.js"></script>
    <script src="../node_modules/uikit/dist/js/uikit-icons.min.js"></script>
</body>
</html>

<?php

if (isset($_POST["simpan"])) {
    $username = htmlspecialchars($_POST["username"]);

    // Validasi
    validasiUsername($username);

    // Ubah data
    mysqli_query($connect, "UPDATE admin SET username = '$username' WHERE id_admin = '$idAdmin'");

    if (mysqli_affected_rows($connect) > 0) {
        echo "
            <script>
                Swal.fire('Data Berhasil Di Update', '', 'success').then(function() {
                    window.location = 'admin.php';
                });
            </script>
        ";
    } else {
        echo "
            <script>
                Swal.fire('Data Gagal Di Update', '', 'error').then(function() {
                    window.location = 'admin.php';
                });
            </script>
        ";
        echo mysqli_error($connect);
    }
}

?>