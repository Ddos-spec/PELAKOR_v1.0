<?php
session_start();
include 'connect-db.php';
include 'functions/functions.php';

cekAdmin();

$jumlahDataPerHalaman = 6; // Changed to 6 to show 2 complete rows
$query = mysqli_query($connect,"SELECT * FROM pelanggan");
$jumlahData = mysqli_num_rows($query);
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);

if (isset($_GET["page"])){
    $halamanAktif = $_GET["page"];
} else {
    $halamanAktif = 1;
}

$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;
$pelanggan = mysqli_query($connect,"SELECT * FROM pelanggan ORDER BY id_pelanggan DESC LIMIT $awalData, $jumlahDataPerHalaman");

if (isset($_POST["cari"])) {
    $keyword = htmlspecialchars($_POST["keyword"]);
    $query = "SELECT * FROM pelanggan WHERE 
        nama LIKE '%$keyword%' OR
        kota LIKE '%$keyword%' OR
        email LIKE '%$keyword%' OR
        alamat LIKE '%$keyword%'
        ORDER BY id_pelanggan DESC
        LIMIT $awalData, $jumlahDataPerHalaman";
    $pelanggan = mysqli_query($connect,$query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "headtags.html"; ?>
    <title>List Pelanggan</title>
    <style>
        .card-image { 
            height: 250px;
            overflow: hidden;
            cursor: pointer;
        }
        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .card-rating {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
        }
        .card-action {
            display: flex;
            justify-content: space-between;
        }
        .modal {
            max-height: 80% !important;
        }
        .card-contact {
            color: #666;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="container">
        <h3 class="header light center">List Pelanggan</h3>
        
        <!-- searching -->
        <div class="row">
            <form class="col s12 center" action="" method="post">
                <div class="input-field inline">
                    <input type="text" size=40 name="keyword" placeholder="Keyword">
                    <button type="submit" class="btn waves-effect blue darken-2" name="cari">
                        <i class="material-icons">search</i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Card Container -->
        <div class="row">
            <?php foreach ($pelanggan as $dataPelanggan) : ?>
            <div class="col s12 m6 l4">
                <div class="card">
                    <div class="card-image" onclick="showDetails(<?= htmlspecialchars(json_encode($dataPelanggan)) ?>)">
                        <img src="https://via.placeholder.com/300x300/randomimage.jpg" alt="Pelanggan">
                        <span class="card-rating">
                            <i class="material-icons">star</i> 4.5
                        </span>
                    </div>
                    <div class="card-content">
                        <span class="card-title"><?= $dataPelanggan["nama"] ?></span>
                        <div class="card-contact">
                            <i class="material-icons tiny">phone</i> <?= $dataPelanggan["telp"] ?>
                        </div>
                        <div class="card-action">
                            <a class="btn blue darken-2" href="ganti-kata-sandi.php?id=<?= $dataPelanggan['id_pelanggan'] ?>&type=pelanggan">
                                <i class="material-icons">lock_reset</i>
                            </a>
                            <a class="btn red darken-2" href="list-pelanggan.php?hapus=<?= $dataPelanggan['id_pelanggan'] ?>" 
                               onclick="return confirm('Apakah anda yakin ingin menghapus data ?')">
                                <i class="material-icons">delete</i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach ?>
        </div>

        <!-- Pagination -->
        <div class="row center">
            <ul class="pagination">
                <?php if($halamanAktif > 1) : ?>
                    <li class="waves-effect">
                        <a href="?page=<?= $halamanAktif - 1; ?>">
                            <i class="material-icons">chevron_left</i>
                        </a>
                    </li>
                <?php endif; ?>
                
                <?php for($i = 1; $i <= $jumlahHalaman; $i++) : ?>
                    <?php if($i == $halamanAktif) : ?>
                        <li class="active blue darken-2"><a href="?page=<?= $i; ?>"><?= $i ?></a></li>
                    <?php else : ?>
                        <li class="waves-effect"><a href="?page=<?= $i; ?>"><?= $i ?></a></li>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if($halamanAktif < $jumlahHalaman) : ?>
                    <li class="waves-effect">
                        <a href="?page=<?= $halamanAktif + 1; ?>">
                            <i class="material-icons">chevron_right</i>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

<!-- Modal Detail -->
<div id="detailModal" class="modal">
    <div class="modal-content">
        <h4>Detail Pelanggan</h4>
        <div class="row">
            <div class="col s12">
                <ul class="collection">
                    <li class="collection-item">ID Pelanggan: <span id="modal-id-pelanggan"></span></li>
                    <li class="collection-item">Email: <span id="modal-email"></span></li>
                    <li class="collection-item">Kota: <span id="modal-kota"></span></li>
                    <li class="collection-item">Alamat: <span id="modal-alamat"></span></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-green btn-flat">Tutup</a>
    </div>
</div>

<?php include "footer.php"; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('.modal');
        var instances = M.Modal.init(elems);
    });

    function showDetails(data) {
        document.getElementById('modal-id-pelanggan').textContent = data.id_pelanggan;
        document.getElementById('modal-email').textContent = data.email;
        document.getElementById('modal-kota').textContent = data.kota;
        document.getElementById('modal-alamat').textContent = data.alamat;
        
        var modal = M.Modal.getInstance(document.getElementById('detailModal'));
        modal.open();
    }
</script>

</body>
</html>

<?php
if (isset($_GET["hapus"])){
$idPelanggan = $_GET["hapus"];
$query = mysqli_query($connect, "DELETE FROM pelanggan WHERE id_pelanggan = '$idPelanggan'");

if (mysqli_affected_rows($connect) > 0){
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