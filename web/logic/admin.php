<?php

session_start();
include 'connect-db.php';
include 'functions/functions.php';

// klo bukan admin
cekAdmin();

// ambil data admin
$idAdmin = $_SESSION["admin"];
$stmt = $connect->prepare("SELECT * FROM admin WHERE id_admin = ?");
$stmt->bind_param("i", $idAdmin);
$stmt->execute();
$data = $stmt->get_result();
$admin = $data->fetch_assoc();



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
    <div id="body">
        <h3 class="header col s24 light center">Profil Admin</h3>
        <form action="" class="col s18 center" method="post">
            <div class="input-field inline">
                <ul>
                    <li><span>Username : </span><input type="text" size=40 name="username" value="<?= $admin['username'] ?>"></li>
                    <li><button type="submit" class="waves-effect blue darken-2 btn" size=40 name="simpan">Simpan Data</button></li>
                    <br>
                    <li><a class="btn waves-effect waves-light red darken-3" id="download-button"  href="ganti-kata-sandi.php">Ganti Kata Sandi</a></li>
                </ul>
            </div>
        </form>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>

<?php

if ( isset($_POST["simpan"]) ){
    $username = htmlspecialchars($_POST["username"]);

    // VALIDASI
    validasiUsername($username);

    // UBAH DATA
$stmt = $connect->prepare("UPDATE admin SET username = ? WHERE id_admin = ?");
$stmt->bind_param("si", $username, $idAdmin);
$stmt->execute();

    if ( mysqli_affected_rows($connect) > 0){
        echo "
            <script>
                Swal.fire('Data Berhasil Di Update','','success').then(function() {
                    window.location = 'admin.php';
                });
            </script>
        ";
    }else{
        echo "
            <script>
                Swal.fire('Data Gagal Di Update','','success').then(function() {
                    window.location = 'admin.php';
                });
            </script>
        ";
        echo mysqli_error($connect);
    }
}


?>