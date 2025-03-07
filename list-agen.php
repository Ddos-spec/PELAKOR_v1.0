<?php
session_start();
include 'connect-db.php';
include 'functions/functions.php';

// Validasi login admin
cekAdmin();

// Proses approve dan delete agen
if (isset($_GET["approve"])) {
    $idAgen = $_GET["approve"];
    mysqli_query($connect, "UPDATE agen SET status = 'approved' WHERE id_agen = '$idAgen'");
    header("Location: list-agen.php");
    exit;
} elseif (isset($_GET["hapus"])) {
    $idAgen = $_GET["hapus"];
    $result = mysqli_query($connect, "DELETE FROM agen WHERE id_agen = '$idAgen'");
    // Check if the deletion was successful
    if ($result) {
        echo "<script>alert('Agen berhasil dihapus.');</script>";
    } else {
        echo "<script>alert('Gagal menghapus agen.');</script>";
    }
    header("Location: list-agen.php");
    exit;
}

// Konfigurasi pagination untuk agen yang sudah di-approve
$jumlahDataPerHalaman = 6; // 3 card per row, 2 rows
$queryApproved = mysqli_query($connect, "SELECT * FROM agen WHERE status = 'approved'");
$jumlahData = mysqli_num_rows($queryApproved);
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);

if (isset($_GET["page"])) {
    $halamanAktif = $_GET["page"];
} else {
    $halamanAktif = 1;
}

$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;
$approvedAgents = mysqli_query($connect, "SELECT * FROM agen WHERE status = 'approved' ORDER BY id_agen DESC LIMIT $awalData, $jumlahDataPerHalaman");

// Ambil data agen yang pending (menunggu approval)
$pendingAgents = mysqli_query($connect, "SELECT * FROM agen WHERE status = 'pending'");
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
        
        <!-- Searching -->
        <div class="row">
            <div class="col s12 center">
                <div class="input-field inline">
                    <input type="text" name="keyword" placeholder="Cari agen...">
                    <i class="material-icons prefix">search</i>
                </div>
            </div>
        </div>

        <!-- Pending Agents Section -->
        <h4 class="header light center">Pending Approvals</h4>
        <div class="row pending-container">
            <?php while ($pending = mysqli_fetch_assoc($pendingAgents)) : ?>
            <div class="col s12 m6 l4">
                <div class="card">
                    <div class="card-image" onclick="showDetails(<?= htmlspecialchars(json_encode($pending)) ?>)">
                        <img src="img/agen/<?= !empty($pending['foto']) ? $pending['foto'] : 'default.jpg' ?>" 
                             alt="<?= $pending["nama_laundry"] ?>">
                    </div>
                    <div class="card-content">
                        <span class="card-title truncate"><?= $pending["nama_laundry"] ?></span>
                        <div class="card-action">
                            <a class="btn green" href="list-agen.php?approve=<?= $pending['id_agen'] ?>" 
                               onclick="return confirm('Approve this agent?')">
                                <i class="material-icons">check</i>
                            </a>
                            <a class="btn red" href="list-agen.php?hapus=<?= $pending['id_agen'] ?>" 
                               onclick="return confirm('Reject this agent?')">
                                <i class="material-icons">close</i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- Approved Agents Section -->
        <h4 class="header light center">Approved Agents</h4>
        <div class="row card-container">
            <?php while ($dataAgen = mysqli_fetch_assoc($approvedAgents)) : ?>
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
                            <a class="btn blue darken-2" href="ganti-kata-sandi.php?id=<?= $dataAgen['id_agen'] ?>&type=agen" onclick="return confirm('Reset password untuk agen ini?')">
                                <i class="material-icons">lock_reset</i>
                            </a>
                            <a class="btn red darken-2" href="list-agen.php?hapus=<?= $dataAgen['id_agen'] ?>" 
                               onclick="return confirm('Apakah anda yakin ingin menghapus data agen ini?')">
                                <i class="material-icons">delete</i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- Pagination (Server-side default) -->
        <div class="row center">
            <ul class="pagination pagination-default">
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

    <!-- Script JavaScript untuk pencarian AJAX + pagination -->
    <script>
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.modal');
    var instances = M.Modal.init(elems);

    const searchInput = document.querySelector('input[name="keyword"]');
    const cardContainer = document.querySelector('.card-container');
    const paginationDefault = document.querySelector('.pagination-default'); 
    let currentPage = 1;
    let timeoutId = null;

    // Hilangkan pagination default saat AJAX dipakai
    paginationDefault.style.display = 'none';

    // Event listener untuk input pencarian (debounce 300ms)
    searchInput.addEventListener('input', function() {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            currentPage = 1;
            searchData(this.value, currentPage);
        }, 300);
    });

    function searchData(keyword, page) {
        fetch(`ajax/cari.php?type=agen&keyword=${encodeURIComponent(keyword)}&page=${page}`)
            .then(response => response.json())
            .then(json => {
                if(json.error) {
                    console.error(json.error);
                    return;
                }
                updateList(json.data);
                updatePagination(json.totalPages, json.currentPage, keyword);
            })
            .catch(error => console.error('Error:', error));
    }

    function updateList(items) {
        cardContainer.innerHTML = '';
        
        if(!items || items.length === 0) {
            cardContainer.innerHTML = '<div class="col s12 center"><h5>Tidak ada hasil yang ditemukan</h5></div>';
            return;
        }

        items.forEach(item => {
            const rating = parseInt(item.rating) || 0;
            const card = `
                <div class="col s12 m6 l4">
                    <div class="card">
                        <div class="card-image" onclick="showDetails(${JSON.stringify(item)})">
                            <img src="img/agen/${item.foto || 'default.jpg'}" alt="${item.nama_laundry}">
                            <div class="rating-stars">
                                ${'★'.repeat(rating)}${'☆'.repeat(5 - rating)}
                            </div>
                        </div>
                        <div class="card-content">
                            <span class="card-title truncate">${item.nama_laundry}</span>
                            <div class="card-action">
                                <a class="btn blue darken-2" href="ganti-kata-sandi.php?id=${item.id_agen}&type=agen">
                                    <i class="material-icons">lock_reset</i>
                                </a>
                                <a class="btn red darken-2" href="list-agen.php?hapus=${item.id_agen}" 
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

    function updatePagination(totalPages, currentPage, keyword) {
        paginationDefault.innerHTML = '';
        paginationDefault.style.display = 'block';

        let paginationHTML = '';

        if(currentPage > 1) {
            paginationHTML += `
                <li class="waves-effect">
                    <a href="#!" data-page="${currentPage - 1}">
                        <i class="material-icons">chevron_left</i>
                    </a>
                </li>
            `;
        }

        for(let i = 1; i <= totalPages; i++) {
            paginationHTML += `
                <li class="waves-effect ${i === currentPage ? 'active blue darken-2' : ''}">
                    <a href="#!" data-page="${i}">${i}</a>
                </li>
            `;
        }

        if(currentPage < totalPages) {
            paginationHTML += `
                <li class="waves-effect">
                    <a href="#!" data-page="${currentPage + 1}">
                        <i class="material-icons">chevron_right</i>
                    </a>
                </li>
            `;
        }

        paginationDefault.innerHTML = paginationHTML;

        document.querySelectorAll('.pagination-default a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const page = parseInt(this.dataset.page);
                currentPage = page;
                searchData(keyword, page);
            });
        });
    }
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
