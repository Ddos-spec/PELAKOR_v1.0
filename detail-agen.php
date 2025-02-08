<?php
session_start();
include 'connect-db.php';

// Mengambil id agen dari URL
$idAgen = $_GET["id"];

// Ambil data agen
$query = mysqli_query($connect, "SELECT * FROM agen WHERE id_agen = '$idAgen'");
$agen = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "headtags.html"; ?>
    <title><?= $agen["nama_laundry"] ?></title>
    <style>
        /* Ukuran foto profil dikurangi */
        .profile-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ddd;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        /* Container foto dan detail agen diatur agar tidak saling menggeser */
        .profile-container {
            text-align: center;
            margin-top: 20px;
        }
        .profile-details {
            text-align: center;
            margin-top: 20px;
        }
        .price-card {
            text-align: center;
        }
        .price-card button {
            margin-bottom: 10px;
        }
        .price-card div {
            margin-top: 5px;
        }
        /* Styling untuk ulasan */
        .review-container {
            margin-top: 30px;
        }
        /* Styling untuk bintang rating */
        .rating-container {
            text-align: center;
            margin-top: 10px;
        }
        /* Tambahan styling untuk bintang rating agar tampil di tengah */
        .bintang {
            width: 100px;
            margin: 0 auto;  /* Membuat fieldset berada di tengah */
            border: none;
            font-weight: bold;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'header.php'; ?>
    <br><br>
    <!-- Data Agen -->
    <div class="row">
        <div class="col s12 m4 offset-m4 profile-container">
            <img src="img/agen/<?= $agen['foto'] ?>" alt="Foto Agen" class="profile-photo">
            <br>
            <a id="download-button" class="btn red darken-3" href="pesan-laundry.php?id=<?= $idAgen ?>">PESAN LAUNDRY</a>
        </div>
    </div>
    <div class="row profile-details">
        <div class="col s12 center">
            <h3><?= $agen["nama_laundry"] ?></h3>
            <div class="rating-container">
                <?php
                    // Menghitung rating agen
                    $temp = $agen["id_agen"];
                    $queryStar = mysqli_query($connect,"SELECT * FROM transaksi WHERE id_agen = '$temp'");
                    $totalStar = 0;
                    $i = 0;
                    while ($star = mysqli_fetch_assoc($queryStar)) {
                        if ($star["rating"] != 0) {
                            $totalStar += $star["rating"];
                            $i++;
                        }
                    }
                    $fixStar = ($i > 0) ? ceil($totalStar / $i) : 0;
                ?>
                <fieldset class="bintang">
                    <span class="starImg star-<?= $fixStar ?>"></span>
                </fieldset>
            </div>
            <ul>
                <li>Alamat: <?= $agen["alamat"] . ", " . $agen["kota"] ?></li>
                <li>No. HP: <?= $agen["telp"] ?></li>
            </ul>
        </div>
    </div>
    
    <!-- Data Harga (Layanan) -->
    <div class="row">
        <div class="col s12 m4 offset-m2">
            <div class="card-panel grey lighten-5 z-depth-1 price-card">
                <div class="row valign-wrapper">
                    <div class="col s12">
                        <a href="pesan-laundry.php?id=<?= $idAgen ?>&jenis=cuci">
                            <button class="btn blue darken-3">CUCI</button>
                        </a>
                        <?php
                            $harga = mysqli_query($connect, "SELECT * FROM harga WHERE id_agen = '$idAgen' AND jenis = 'cuci'");
                            $harga = mysqli_fetch_assoc($harga);
                            echo "<div>Rp. " . $harga['harga'] . " /Kg</div>";
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col s12 m4">
            <div class="card-panel grey lighten-5 z-depth-1 price-card">
                <div class="row valign-wrapper">
                    <div class="col s12">
                        <a href="pesan-laundry.php?id=<?= $idAgen ?>&jenis=setrika">
                            <button class="btn blue darken-3">SETRIKA</button>
                        </a>
                        <?php
                            $harga = mysqli_query($connect, "SELECT * FROM harga WHERE id_agen = '$idAgen' AND jenis = 'setrika'");
                            $harga = mysqli_fetch_assoc($harga);
                            echo "<div>Rp. " . $harga['harga'] . " /Kg</div>";
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col s12 m4 offset-m4">
            <div class="card-panel grey lighten-5 z-depth-1 price-card">
                <div class="row valign-wrapper">
                    <div class="col s12">
                        <a href="pesan-laundry.php?id=<?= $idAgen ?>&jenis=komplit">
                            <button class="btn blue darken-3">KOMPLIT</button>
                        </a>
                        <?php
                            $harga = mysqli_query($connect, "SELECT * FROM harga WHERE id_agen = '$idAgen' AND jenis = 'komplit'");
                            $harga = mysqli_fetch_assoc($harga);
                            echo "<div>Rp. " . $harga['harga'] . " /Kg</div>";
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <hr><br>
    
    <!-- Ulasan Pengguna -->
    <h3 class="header light center">Ulasan Pengguna</h3>
    <br>
    <div class="row review-container">
        <?php
            $temp = mysqli_query($connect, "SELECT * FROM transaksi WHERE id_agen = $idAgen");
            while ($transaksi = mysqli_fetch_assoc($temp)) :
                $idPelanggan = $transaksi["id_pelanggan"];
                $temp2 = mysqli_query($connect, "SELECT * FROM pelanggan WHERE id_pelanggan = $idPelanggan");
                $pelanggan = mysqli_fetch_assoc($temp2);
        ?>
        <div class="col s12 m3 offset-m1 center">
            <table border="0">
                <tr>
                    <td width="150" rowspan="3">
                        <img src="img/pelanggan/<?= $pelanggan['foto'] ?>" alt="Foto Pelanggan" class="circle responsive-img profile-photo">
                    </td>
                    <td><?= "<h6 class='light'>" . $pelanggan["nama"] . "</h6>"; ?></td>
                </tr>
                <tr>
                    <td>
                        <fieldset class="bintang">
                            <span class="starImg star-<?= $transaksi['rating'] ?>"></span>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <td><?= $transaksi["komentar"]; ?></td>
                </tr>
            </table>
        </div>
        <?php endwhile; ?>
    </div>
    
    <?php include "footer.php"; ?>
</body>
</html>
