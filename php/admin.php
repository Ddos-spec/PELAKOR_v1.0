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
    <link rel="stylesheet" href="../uikit/dist/css/uikit.css"> <!-- Include UIkit CSS -->
    <link rel="stylesheet" href="../uikit/dist/css/custom.css"> <!-- Include custom CSS -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Include SweetAlert2 -->
    <title>Halaman Login</title>
    <style>
        .container {
            min-height: 80vh; /* Adjust this value as needed */
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-card {
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            max-width: 400px; /* Set a max width for the card */
            margin: auto; /* Center the card */
        }
    </style>
</head>
<body>

    <!-- header -->
    <?php include 'header.php'; ?>
    <!-- end header -->

    <!-- body -->
    <div class="container uk-text-center">  
        <div class="login-card uk-card uk-card-default">
            <h3 class="header light">Halaman Login</h3>
            <form action="" method="post" class="uk-form-stacked">
                <div class="uk-margin">
                    <div class="uk-form-controls">
                        <label><input name="akun" value="admin" type="radio"/><span>Admin</span> </label>
                        <label><input name="akun" value="agen" type="radio"/><span>Agen</span> </label>
                        <label><input name="akun" value="pelanggan" type="radio"/><span>Pelanggan</span></label>
                    </div>
                </div>
                <div class="uk-margin">
                    <input type="text" id="email" name="email" class="uk-input" placeholder="Email/Username" required>
                </div>
                <div class="uk-margin">
                    <input type="password" id="password" name="password" class="uk-input" placeholder="Password" required>
                </div>
                <div class="uk-margin">
                    <button class="uk-button uk-button-primary" type="submit" name="login">Login</button>
                </div>
            </form>
        </div>
    </div>
    <!-- end body -->

    <!-- footer -->
    <?php include 'footer.php'; ?>
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
        $stmt = $connect->prepare("SELECT * FROM agen WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row["password"])) {
                $_SESSION["agen"] = $row["id_agen"];
                $_SESSION["login-agen"] = true;
                echo "<script>document.location.href = '../index.php';</script>";
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
                echo "<script>document.location.href = '../index.php';</script>";
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
                echo "<script>document.location.href = 'admin.php';</script>"; // Redirect ke admin.php
                exit;
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