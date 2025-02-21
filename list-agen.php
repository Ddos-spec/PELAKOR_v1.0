<?php
session_start();
include 'connect-db.php';
include 'functions/functions.php';

// Validasi login
cekAdmin();

// Konfigurasi pagination
$jumlahDataPerHalaman = 6; // 3 card per row, 2 rows
$query = mysqli_query($connect,"SELECT * FROM agen");
$jumlahData = mysqli_num_rows($query);
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);

if (isset($_GET["page"])){
    $halamanAktif = $_GET["page"];
} else {
    $halamanAktif = 1;
}

$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;
$agen = mysqli_query($connect,"SELECT * FROM agen ORDER BY id_agen DESC LIMIT $awalData, $jumlahDataPerHalaman");

// Jika masih menggunakan pencarian via form POST, kode di bawah tidak lagi dipakai:
// if (isset($_POST["cari"])) {
//     $keyword = htmlspecialchars($_POST["keyword"]);
//     $query = "SELECT * FROM agen WHERE 
//         nama_laundry LIKE '%$keyword%' OR
//         nama_pemilik LIKE '%$keyword%' OR
//         kota LIKE '%$keyword%' OR
//         email LIKE '%$keyword%' OR
//         alamat LIKE '%$keyword%'
//         ORDER BY id_agen DESC
//         LIMIT $awalData, $jumlahDataPerHalaman";
//     $agen = mysqli_query($connect,$query);
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "headtags.html"; ?>
    <title>Data Agen</title>
    <style>
        .card-image { 
            height: 250px;
            overflow: hidden;
            cursor: pointer;
            position: relative;
        }
        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .rating-stars {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.7);
            color: #ffd700;
            padding: 5px 10px;
            border-radius: 15px;
        }
        .card-action {
            display: flex;
            justify-content: space-between;
            padding: 10px;
        }
        .modal {
            max-height: 80% !important;
        }
    </style>
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="container">
        <h3 class="header light center">List Agen</h3>
        
        <!-- Updated Searching -->
        <div class="row">
            <div class="col s12 center">
                <div class="input-field inline">
                    <input type="text" id="searchAgen" placeholder="Cari agen...">
                    <i class="material-icons prefix">search</i>
                </div>
            </div>
        </div>

        <!-- Card Container -->
        <div class="row">
            <?php foreach ($agen as $dataAgen) : ?>
            <div class="col s12 m6 l4">
                <div class="card">
                    <div class="card-image" onclick="showDetails(<?= htmlspecialchars(json_encode($dataAgen)) ?>)">
                        <img src="img/agen/<?= !empty($dataAgen['foto']) ? $dataAgen['foto'] : 'default.jpg' ?>" 
                             alt="<?= $dataAgen["nama_laundry"] ?>">
                        <div class="rating-stars">
                            <?php 
                            $rating = isset($dataAgen['rating']) ? (int)$dataAgen['rating'] : 0;
                            echo str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
                            ?>
                        </div>
                    </div>
                    <div class="card-content">
                        <span class="card-title truncate"><?= $dataAgen["nama_laundry"] ?></span>
                        <div class="card-action">
                            <a class="btn blue darken-2" href="ganti-kata-sandi.php?id=<?= $dataAgen['id_agen'] ?>&type=agen">
                                <i class="material-icons">lock_reset</i>
                            </a>
                            <a class="btn red darken-2" href="list-agen.php?hapus=<?= $dataAgen['id_agen'] ?>" 
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
            <h4>Detail Agen</h4>
            <div class="row">
                <div class="col s12">
                    <ul class="collection">
                        <li class="collection-item">Nama Pemilik: <span id="modal-nama-pemilik"></span></li>
                        <li class="collection-item">No Telp: <span id="modal-telp"></span></li>
                        <li class="collection-item">Plat Driver: <span id="modal-plat"></span></li>
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

    <!-- Script JavaScript untuk pencarian -->
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchAgen');
    // Mengasumsikan baris kedua di dalam container adalah card container
    const cardContainer = document.querySelectorAll('.container .row')[1];
    
    searchInput.addEventListener('keyup', function() {
        const keyword = this.value.trim();
        searchAgen(keyword);
    });

    function searchAgen(keyword) {
        fetch(`ajax/cari.php?action=searchAgen&keyword=${encodeURIComponent(keyword)}`)
            .then(response => response.json())
            .then(data => updateAgenList(data))
            .catch(error => console.error('Error:', error));
    }

    function updateAgenList(agents) {
        cardContainer.innerHTML = '';
        
        if(agents.length === 0) {
            cardContainer.innerHTML = '<div class="col s12 center"><p>Tidak ada hasil yang ditemukan</p></div>';
            return;
        }

        agents.forEach(agent => {
            // Gunakan template card yang sama seperti sebelumnya
            const card = `
                <div class="col s12 m6 l4">
                    <div class="card">
                        <div class="card-image" onclick="showDetails(${JSON.stringify(agent)})">
                            <img src="img/agen/${agent.foto || 'default.jpg'}" alt="${agent.nama_laundry}">
                            <div class="rating-stars">
                                ${'★'.repeat(agent.rating)}${'☆'.repeat(5-agent.rating)}
                            </div>
                        </div>
                        <div class="card-content">
                            <span class="card-title truncate">${agent.nama_laundry}</span>
                            <div class="card-action">
                                <a class="btn blue darken-2" href="ganti-kata-sandi.php?id=${agent.id_agen}&type=agen">
                                    <i class="material-icons">lock_reset</i>
                                </a>
                                <a class="btn red darken-2" href="list-agen.php?hapus=${agent.id_agen}" 
                                   onclick="return confirm('Apakah anda yakin ingin menghapus data ?')">
                                    <i class="material-icons">delete</i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            cardContainer.insertAdjacentHTML('beforeend', card);
        });
    }
});
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('.modal');
            var instances = M.Modal.init(elems);
        });

        function showDetails(data) {
            document.getElementById('modal-nama-pemilik').textContent = data.nama_pemilik;
            document.getElementById('modal-telp').textContent = data.telp;
            document.getElementById('modal-plat').textContent = data.plat_driver;
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
    $idAgen = $_GET["hapus"];
    $query = mysqli_query($connect, "DELETE FROM agen WHERE id_agen = '$idAgen'");
    
    if (mysqli_affected_rows($connect) > 0){
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
