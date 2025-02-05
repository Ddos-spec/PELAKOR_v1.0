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
    <link rel="stylesheet" href="../uikit/dist/css/uikit.min.css" />
    <title>Registrasi Pelanggan</title>
    <style>
        .uk-card {
            padding: 20px;
            border-radius: 20px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .uk-card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        .uk-card-primary {
            background-color: #1e87f0;
            color: white;
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }
        .uk-button-primary {
            background-color: white;
            color: #1e87f0;
        }
        .uk-icon-large {
            font-size: 48px;
        }
        .uk-text-bold {
            font-weight: bold;
        }
        .button-container {
            margin-top: auto;
        }
    </style>
</head>
<body>

    <!-- header -->
    <?php include 'header.php'; ?>
    <!-- end header -->

    <h3 class="uk-heading-line uk-text-center"><span>Registrasi Pelanggan</span></h3>

    <!-- body -->
    <div class="uk-container">
        <div class="uk-grid-match uk-child-width-1-2@m uk-flex-center" uk-grid>
            <div>
                <div class="uk-card uk-card-default uk-card-body">
                    <form action="" method="POST">
                        <div class="uk-margin">
                            <label for="nama">Nama</label>
                            <input class="uk-input" type="text" id="nama" placeholder="Nama" name="nama">
                        </div>
                        <div class="uk-margin">
                            <label for="email">Email</label>
                            <input class="uk-input" type="text" id="email" placeholder="E-mail" name="email">
                        </div>
                        <div class="uk-margin">
                            <label for="telp">No Telp</label>
                            <input class="uk-input" type="text" id="telp" placeholder="No Telp" name="noTelp">
                        </div>
                        <div class="uk-margin">
                            <label for="kota">Kota / Kabupaten</label>
                            <input class="uk-input" type="text" id="kota" placeholder="Kota / Kabupaten" name="kota">
                        </div>
                        <div class="uk-margin">
                            <label for="alamat">Alamat Lengkap</label>
                            <input class="uk-input" type="text" id="alamat" placeholder="Alamat Lengkap" name="alamat">
                        </div>
                        <div class="uk-margin">
                            <label for="password">Password</label>
                            <input class="uk-input" type="password" id="password" placeholder="Password" name="password">
                        </div>
                        <div class="uk-margin">
                            <label for="repassword">Re-type Password</label>
                            <input class="uk-input" type="password" id="repassword" placeholder="Re-type Password" name="password2">
                        </div>
                        <div class="uk-text-center uk-margin">
                            <button class="uk-button uk-button-primary uk-button-large" type="submit" name="registrasi">Daftar</button>
                        </div>
                    </form>
                </div>
            </div>
            <div>
                <div class="uk-card uk-card-primary uk-card-body uk-text-center">
                    <div class="uk-flex uk-flex-center uk-flex-middle uk-margin-bottom">
                        <span uk-icon="user" class="uk-icon-large"></span>
                    </div>
                    <h4 class="uk-text-bold">Mau Registrasi Agen?</h4>
                    <p>Dapatkan akses eksklusif, komisi besar, dan dukungan penuh dari kami.</p>
                    <br>
                    <div class="button-container uk-margin-large-top">
                        <a class="uk-button uk-button-primary uk-button-large uk-text-bold" href="registrasi-agen.php">Registrasi Sebagai Agen</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end body -->

    <!-- footer -->
    <?php include "footer.php"; ?>
    <!-- end footer -->

    <script src="../uikit/dist/js/uikit.min.js"></script>
    <script src="../uikit/dist/js/uikit-icons.min.js"></script>
</body>
</html>



<?php

// fungsi registrasi
function registrasi ($data) {
    global $connect;

    //mengambil data
    $nama = htmlspecialchars($data["nama"]);
    $email = htmlspecialchars($data["email"]);
    $noTelp = htmlspecialchars($data["noTelp"]);
    $kota = htmlspecialchars($data["kota"]);
    $alamat = htmlspecialchars($data["alamat"]);
    $password = htmlspecialchars($data["password"]);
    $password2 = htmlspecialchars($data["password2"]);

    // validasi
    validasiNama($nama);
    validasiEmail($email);
    validasiTelp($noTelp);
    validasiNama($kota);

    // enkripsi password
    $password = mysqli_real_escape_string($connect , $data["password"]);
    $password2 = mysqli_real_escape_string($connect , $data["password2"]);

    // cek username apakah ada yg sama        
    $result = mysqli_query($connect, "SELECT email FROM pelanggan WHERE email = '$email'");
    if ( mysqli_fetch_assoc($result) ){ //jika ada (TRUE)
        echo "
            <script>
                Swal.fire('Pendaftaran Gagal','Email Sudah Terdaftar','error');
            </script>
        ";
        // RETURN FALSE
        return false;
    }

    // cek konfirmasi password
    if ($password != $password2) {
        echo "
            <script>   
                Swal.fire('Pendaftaran Gagal','Password Tidak Sama','error');
            </script>
        ";
        return false;
    }

    // enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // masukkan data user ke db
    $stmt = $connect->prepare("INSERT INTO pelanggan (nama, email, telp, kota, alamat, foto, password) VALUES (?, ?, ?, ?, ?, 'default.png', ?)");
    $stmt->bind_param("ssssss", $nama, $email, $noTelp, $kota, $alamat, $password);
    $stmt->execute();

    // RETURN TRUE
    return $stmt->affected_rows;
}

// ketika tombol registrasi di klik
if ( isset($_POST["registrasi"]) ){
    if ( registrasi($_POST) > 0 ) {
        $email = $_POST["email"];
        $query = mysqli_query($connect, "SELECT * FROM pelanggan WHERE email = '$email'");
        $pelanggan = mysqli_fetch_assoc($query);
        $_SESSION["pelanggan"] = $pelanggan["id_pelanggan"];
        $_SESSION["login-pelanggan"] = true;
        echo "
            <script>
                Swal.fire('Pendaftaran Berhasil','Selamat Bergabung Dengan LaundryKu','success').then(function() {
                    window.location = 'index.php';
                });
            </script>
        ";
    } else {
        echo mysqli_error($connect);
    }
}

?>