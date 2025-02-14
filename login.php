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
    <title>Halaman Login Pelanggan</title>
</head>
<body>

    <!-- header -->
    <?php include 'header.php'; ?>
    <!-- end header -->

    <!-- body -->
    <div class="container">
        <div class="row">
            <div class="col s12 m6 offset-m3">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title center">Login Pelanggan</span>
                        <form action="" method="post">
                            <div class="input-field">
                                <input type="text" id="email" name="email" placeholder="Email" required>
                                <label for="email">Email</label>
                            </div>
                            <div class="input-field">
                                <input type="password" id="password" name="password" placeholder="Password" required>
                                <label for="password">Password</label>
                            </div>
                            <div class="center">
                                <button class="waves-effect blue darken-2 btn" type="submit" name="login">Login</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-action center">
                        <a href="registrasi.php">Belum punya akun? Daftar di sini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end body -->

    <!-- footer -->
    <?php include "footer.php"; ?>
    <!-- end footer -->

    <!-- Materialize JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>

<?php

if ( isset($_POST["login"]) ){
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);

    validasiEmail($email);

    //cek apakah ada email atau tidak
    $result = mysqli_query($connect, "SELECT * FROM pelanggan WHERE email = '$email'");
    
    //jika ada username
    if ( mysqli_num_rows($result) === 1 ){   //fungsi menghitung jumlah baris di db
        
        //memasukkan data db ke array assosiative
        $data = mysqli_fetch_assoc($result);

        //cek apakah password sama
        if ( password_verify($password, $data["password"]) ){
            //session login 
            $_SESSION["pelanggan"] = $data["id_pelanggan"];
            $_SESSION["login-pelanggan"] = true;

            echo "
                <script>
                    document.location.href = 'index.php';
                </script>
            ";
            
        }else {
            echo "
                <script>
                    Swal.fire('Gagal Login','Password Yang Anda Masukkan Salah','warning');
                </script>
            ";
        }
    }else {
        echo "
            <script>
                Swal.fire('Gagal Login','Email Belum Terdaftar','warning');
            </script>
        ";
    }
}

?>