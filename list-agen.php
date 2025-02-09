<?php
session_start();
include 'connect-db.php';
include 'functions/functions.php';

// Validasi login
cekAdmin();

// Ambil semua data agen tanpa pagination
$query = "SELECT * FROM agen ORDER BY id_agen DESC";
if (isset($_POST["cari"])) {
    $keyword = htmlspecialchars($_POST["keyword"]);
    $query = "SELECT * FROM agen WHERE 
        nama_laundry LIKE '%$keyword%' OR
        nama_pemilik LIKE '%$keyword%' OR
        kota LIKE '%$keyword%' OR
        email LIKE '%$keyword%' OR
        alamat LIKE '%$keyword%'
        ORDER BY id_agen DESC";
}
$agen = mysqli_query($connect, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include "headtags.html"; ?>
  <title>Data Agen</title>
  <!-- Materialize CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <style>
    /* Card lebih panjang agar gambar tidak terpotong */
    .card {
      height: 450px; /* Tinggi card diperbesar */
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      position: relative;
    }
    /* Gambar menggunakan object-fit: cover */
    .card .card-image img {
      width: 100%;
      height: 250px; /* Tinggi gambar tetap */
      min-height: 200px;
      max-height: 300px;
      object-fit: cover; /* Gambar selalu mengisi area */
      background-color: #f1f1f1; /* Background abu-abu jika diperlukan */
    }
    .card .card-content {
      flex-grow: 1;
    }
    /* Grid: 3 card per baris; jika baris terakhir kurang, tetap di tengah */
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
    /* Grup tombol pada card-content */
    .btn-group a {
      margin-right: 5px;
    }
  </style>
</head>
<body>
  <!-- Header -->
  <?php include 'header.php'; ?>

  <h3 class="header light center">List Agen</h3>
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
      <?php foreach ($agen as $dataAgen) : ?>
      <div class="col s12 m4">
        <div class="card">
          <!-- Card Image dengan efek waves dan activator -->
          <div class="card-image waves-effect waves-block waves-light">
            <?php if ($dataAgen["foto"]) : ?>
              <img class="activator" src="img/agen/<?= $dataAgen["foto"] ?>" alt="<?= $dataAgen["nama_laundry"] ?>">
            <?php else : ?>
              <img class="activator" src="img/default.jpg" alt="Default Image">
            <?php endif; ?>
            <!-- Tombol tiga titik (activator) untuk menampilkan card-reveal -->
            <span class="card-title activator grey-text text-darken-4">
              <i class="material-icons">more_vert</i>
            </span>
          </div>
          <!-- Card Content: Menampilkan ID Agen dan Nama Laundry -->
          <div class="card-content">
            <p>ID Agen: <?= $dataAgen["id_agen"] ?></p>
            <p>Nama Laundry: <?= $dataAgen["nama_laundry"] ?></p>
            <div class="btn-group" style="margin-top:10px;">
              <a href="reset-password-admin.php?type=agen&id=<?= $dataAgen['id_agen'] ?>" class="btn blue darken-2">
                <i class="material-icons">lock_reset</i>
              </a>
              <a href="list-agen.php?hapus=<?= $dataAgen['id_agen'] ?>" class="btn red darken-2" onclick="return confirm('Apakah anda yakin ingin menghapus data ?')">
                <i class="material-icons">delete</i>
              </a>
            </div>
          </div>
          <!-- Card Reveal: Menampilkan detail agen -->
          <div class="card-reveal">
            <span class="card-title grey-text text-darken-4">
              Detail Agen
              <i class="material-icons right">close</i>
            </span>
            <p>Nama Pemilik: <?= $dataAgen["nama_pemilik"] ?></p>
            <p>No Telp: <?= $dataAgen["telp"] ?></p>
            <p>Email: <?= $dataAgen["email"] ?></p>
            <p>Plat Driver: <?= $dataAgen["plat_driver"] ?></p>
            <p>Kota: <?= $dataAgen["kota"] ?></p>
            <p>Alamat: <?= $dataAgen["alamat"] ?></p>
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
      // Inisialisasi semua komponen Materialize
      M.AutoInit();
    });
  </script>
</body>
</html>
<?php
if (isset($_GET["hapus"])) {
    $idAgen = $_GET["hapus"];
    $query = mysqli_query($connect, "DELETE FROM agen WHERE id_agen = '$idAgen'");
    if (mysqli_affected_rows($connect) > 0) {
        echo "
            <script>
                Swal.fire('Data Agen Berhasil Di Hapus','','success').then(function(){
                    window.location = 'list-agen.php';
                });
            </script>
        ";
    }
}
?>
