<?php
include "../connect-db.php";

$keyword = htmlspecialchars($_GET["keyword"]);

// Konfigurasi pagination tetap sama
$jumlahDataPerHalaman = 3;
$query = "SELECT * FROM agen WHERE 
    kota LIKE '%$keyword%' OR
    nama_laundry LIKE '%$keyword%'
";
$result = mysqli_query($connect, $query);
$jumlahData = mysqli_num_rows($result);
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
$halamanAktif = isset($_GET["page"]) ? $_GET["page"] : 1;
$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

$query = "SELECT * FROM agen WHERE 
    kota LIKE '%$keyword%' OR
    nama_laundry LIKE '%$keyword%'
    LIMIT $awalData, $jumlahDataPerHalaman
";
$agen = mysqli_query($connect, $query);
?>

<!-- Pagination -->
<div id="search">
    <ul class="pagination center">
        <?php if($halamanAktif > 1): ?>
            <li class="disabled-effect blue darken-1">
                <a href="?page=<?= $halamanAktif - 1; ?>"><i class="material-icons">chevron_left</i></a>
            </li>
        <?php endif; ?>
        <?php for($i = 1; $i <= $jumlahHalaman; $i++): ?>
            <?php if($i == $halamanAktif): ?>
                <li class="active grey"><a href="?page=<?= $i; ?>"><?= $i; ?></a></li>
            <?php else: ?>
                <li class="waves-effect blue darken-1"><a href="?page=<?= $i; ?>"><?= $i; ?></a></li>
            <?php endif; ?>
        <?php endfor; ?>
        <?php if($halamanAktif < $jumlahHalaman): ?>
            <li class="waves-effect blue darken-1">
                <a class="page-link" href="?page=<?= $halamanAktif + 1; ?>"><i class="material-icons">chevron_right</i></a>
            </li>
        <?php endif; ?>
    </ul>
</div>
<!-- End Pagination -->

<!-- List Agen (sama seperti index) -->
<div class="container">
    <div class="section">
        <div class="row">
            <?php while($dataAgen = mysqli_fetch_assoc($agen)): ?>
                <div class="col s12 m4">
                    <div class="card">
                        <div class="card-image">
                            <!-- Sesuaikan path gambar jika diperlukan -->
                            <img src="./img/agen/<?= $dataAgen['foto'] ?>" alt="Foto Agen">
                        </div>
                        <div class="card-content">
                            <?php
                                $temp = $dataAgen["id_agen"];
                                $queryStar = mysqli_query($connect, "SELECT * FROM transaksi WHERE id_agen = '$temp'");
                                $totalStar = 0;
                                $i = 0;
                                while ($star = mysqli_fetch_assoc($queryStar)) {
                                    if ($star["rating"] != 0) {
                                        $totalStar += $star["rating"];
                                        $i++;
                                    }
                                }
                                $fixStar = $i > 0 ? ceil($totalStar / $i) : 0;
                            ?>
                            <div class="center">
                                <fieldset class="bintang">
                                    <span class="starImg star-<?= $fixStar ?>"></span>
                                </fieldset>
                            </div>
                            <p>Alamat: <?= $dataAgen["alamat"] . ", " . $dataAgen["kota"] ?><br>Telp: <?= $dataAgen["telp"] ?></p>
                        </div>
                        <div class="card-action">
                            <a href="detail-agen.php?id=<?= $dataAgen['id_agen'] ?>"><?= $dataAgen["nama_laundry"] ?></a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <br><br>
</div>
