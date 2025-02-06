<?php
session_start();
include 'connect-db.php';
include 'functions/functions.php';

// Cek apakah admin sudah login
cekAdmin();

$swalScript = ""; // Variabel untuk menampung script SweetAlert

if (isset($_GET['type']) && isset($_GET['id'])) {
    $userType = $_GET['type'];
    $userId = $_GET['id'];

    if (isset($_POST['submit'])) {
        $password = htmlspecialchars($_POST['password']);
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Cek apakah password baru sama dengan password lama
        if ($userType == 'agen') {
            $query = mysqli_query($connect, "SELECT password FROM agen WHERE id_agen = '$userId'");
        } else {
            $query = mysqli_query($connect, "SELECT password FROM pelanggan WHERE id_pelanggan = '$userId'");
        }
        $user = mysqli_fetch_assoc($query);

        if (password_verify($password, $user['password'])) {
            $swalScript = "Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Kata sandi baru tidak boleh sama dengan kata sandi lama.'
            });";
        } else {
            // Update password
            if ($userType == 'agen') {
                mysqli_query($connect, "UPDATE agen SET password = '$passwordHash' WHERE id_agen = '$userId'");
            } else {
                mysqli_query($connect, "UPDATE pelanggan SET password = '$passwordHash' WHERE id_pelanggan = '$userId'");
            }
            $swalScript = "Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Kata sandi berhasil direset.'
            }).then(function() {
                window.location.href = 'list-" . ($userType == 'agen' ? 'agen' : 'pelanggan') . ".php';
            });";
        }
    }
} else {
    $swalScript = "Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: 'Data tidak valid.'
    }).then(function() {
        window.location.href = 'list-agen.php';
    });";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'headtags.html'; ?>
    <!-- Memuat SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Reset Kata Sandi</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h3 class="header light center">Reset Kata Sandi</h3>
        <form action="" method="post">
            <div class="input-field">
                <input type="password" name="password" required>
                <label for="password">Kata Sandi Baru</label>
            </div>
            <button type="submit" name="submit" class="btn waves-effect blue darken-2">Reset Kata Sandi</button>
        </form>
    </div>
    <?php include 'footer.php'; ?>

    <!-- Menampilkan SweetAlert jika ada -->
    <?php
    if (!empty($swalScript)) {
        echo "<script>$swalScript</script>";
    }
    ?>
</body>
</html>
