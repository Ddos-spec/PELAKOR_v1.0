<?php
session_start();
include 'connect-db.php';
include 'functions/functions.php';

cekAdmin();

// Mengambil semua data pelanggan tanpa pagination
$pelanggan = mysqli_query($connect, "SELECT * FROM pelanggan ORDER BY id_pelanggan DESC");

// Jika tombol cari ditekan, perbarui query pencarian
if (isset($_POST["cari"])) {
    $keyword = htmlspecialchars($_POST["keyword"]);
    $query = "SELECT * FROM pelanggan WHERE 
        nama LIKE '%$keyword%' OR
        kota LIKE '%$keyword%' OR
        email LIKE '%$keyword%' OR
        alamat LIKE '%$keyword%'
        ORDER BY id_pelanggan DESC";
    $pelanggan = mysqli_query($connect, $query);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Materialize CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <?php include "headtags.html"; ?>
  <title>List Pelanggan</title>
  <style>
    /* Card diperpanjang agar foto tidak terpotong */
    .card {
      height: 450px; /* Meningkatkan tinggi card */
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      position: relative;
    }
    .card .card-image img {
      height: 300px; /* Meningkatkan tinggi area gambar */
      object-fit: cover; /* Gambar selalu mengisi area (cover) */
    }
    .card .card-content {
      flex-grow: 1;
    }
    /* Grid: Tampilkan 3 card per baris; baris terakhir tetap di tengah */
    .row {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
    }
    .col.s12.m4 {
      display: flex;
      flex-direction: column;
    }
    /* Posisi ikon activator (titik tiga) di sudut kanan atas gambar */
    .card .card-image .card-title.activator {
      position: absolute;
      top: 10px;
      right: 10px;
      cursor: pointer;
    }
    /* Pengaturan grup tombol pada card-content */
    .btn-group a {
      margin-right: 5px;
    }
  </style>
</head>
<body>

  <?php include 'header.php'; ?>

  <h3 class="header light center">List Pelanggan</h3>
  <br>

  <!-- Searching -->
  <form class="col s12 center" action="" method="post">
    <div class="input-field inline">
      <input type="text" size="40" name="keyword" placeholder="Keyword">
      <button type="submit" class="btn waves-effect blue darken-2" name="cari">
        <i class="material-icons">send</i>
      </button>
    </div>
  </form>
  <!-- End Searching -->

  <div class="container">
    <div class="row">
      <?php foreach ($pelanggan as $dataPelanggan) : ?>
      <div class="col s12 m4">
        <div class="card">
          <!-- Card Image dengan efek waves dan activator -->
          <div class="card-image waves-effect waves-block waves-light">
            <?php if ($dataPelanggan["foto"]) : ?>
              <img class="activator" src="img/pelanggan/<?= $dataPelanggan["foto"] ?>" alt="<?= $dataPelanggan["nama"] ?>">
            <?php else : ?>
              <img class="activator" src="img/default.jpg" alt="Default Image">
            <?php endif; ?>
            <!-- Tombol tiga titik (activator) untuk reveal detail -->
            <span class="card-title activator grey-text text-darken-4">
              <i class="material-icons">more_vert</i>
            </span>
          </div>
          <!-- Card Content: Menampilkan ID dan Nama pelanggan beserta tombol -->
          <div class="card-content">
            <p>ID: <?= $dataPelanggan["id_pelanggan"] ?></p>
            <p>Nama: <?= $dataPelanggan["nama"] ?></p>
            <div class="btn-group" style="margin-top:10px;">
              <a href="reset-password-admin.php?type=pelanggan&id=<?= $dataPelanggan['id_pelanggan'] ?>" class="btn blue darken-2">
                <i class="material-icons">lock_reset</i>
              </a>
              <a href="list-pelanggan.php?hapus=<?= $dataPelanggan['id_pelanggan'] ?>" class="btn red darken-2" onclick="return confirm('Apakah anda yakin ingin menghapus data ?')">
                <i class="material-icons">delete</i>
              </a>
            </div>
          </div>
          <!-- Card Reveal: Menampilkan detail pelanggan -->
          <div class="card-reveal">
            <span class="card-title grey-text text-darken-4">
              Detail Pelanggan
              <i class="material-icons right">close</i>
            </span>
            <p>No Telp: <?= $dataPelanggan["telp"] ?></p>
            <p>Email: <?= $dataPelanggan["email"] ?></p>
            <p>Kota: <?= $dataPelanggan["kota"] ?></p>
            <p>Alamat: <?= $dataPelanggan["alamat"] ?></p>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <?php include "footer.php"; ?>

  <!-- Materialize JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Inisialisasi semua komponen Materialize secara otomatis
      M.AutoInit();
    });
  </script>
</body>
</html>
<?php
if (isset($_GET["hapus"])) {
    $idPelanggan = $_GET["hapus"];
    $query = mysqli_query($connect, "DELETE FROM pelanggan WHERE id_pelanggan = '$idPelanggan'");
    if (mysqli_affected_rows($connect) > 0) {
        echo "
            <script>
                Swal.fire('Data Pelanggan Berhasil Di Hapus','','success').then(function(){
                    window.location = 'list-pelanggan.php';
                });
            </script>
        ";
    }
}
?>
