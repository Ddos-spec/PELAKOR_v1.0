<?php

//session 
session_start();
include 'connect-db.php';

// mengambil id agen dg method get
$idAgen = $_GET["id"];

// ambil data agen
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
</head>

<body>

    <!-- header -->
    <?php include 'header.php'; ?>
    <!-- end header -->
    <br><br>
    <!-- data agen -->
    <div class="row">
        <div class="col s2 offset-s4">
            <img src="img/agen/<?= $agen['foto'] ?>" class="circle responsive-img" width="70%" />
            <a id="download-button" class="btn red darken-3" href="pesan-laundry.php?id=<?= $idAgen ?>">PESAN LAUNDRY</a>
        </div>
        <div class="col s6">
            <h3><?= $agen["nama_laundry"] ?></h3>
            <ul>
                <li>
                    <?php
                        $temp = $agen["id_agen"];
                        $queryStar = mysqli_query($connect,"SELECT * FROM transaksi WHERE id_agen = '$temp'");
                        $totalStar = 0;
                        $i = 0;
                        while ($star = mysqli_fetch_assoc($queryStar)){

                            // kalau belum kasi rating gak dihitung
                            if ($star["rating"] != 0){
                                $totalStar += $star["rating"];
                                $i++;
                                $fixStar = ceil($totalStar / $i);
                            }
                        }
                            
                        if ( $totalStar == 0 ) {
                    ?>
                        <fieldset class="bintang"><span class="starImg star-0"></span></fieldset>
                    <?php }else { ?>
                        <fieldset class="bintang"><span class="starImg star-<?= $fixStar ?>"></span></fieldset>
                    <?php } ?>
                </li>
                <li>Alamat : <?= $agen["alamat"] . ", " . $agen["kota"] ?></li>
                <li>No. HP : <?= $agen["telp"] ?></li>
            </ul>
        </div>
    </div>

        <!-- data harga -->
    <div class="row">
        <div class="col s3 offset-s2">
            <div class="card-panel grey lighten-5 z-depth-1">
                <div class="row valign-wrapper">
                    <a href="pesan-laundry.php?id=<?= $idAgen ?>&jenis=cuci" style="margin:0% 15%"><button class="btn blue darken-3">CUCI</button></a>
                    <div>
                        <?php
                            $harga = mysqli_query($connect, "SELECT * FROM harga WHERE id_agen = '$idAgen' AND jenis = 'cuci'");
                            $harga = mysqli_fetch_assoc($harga);
                            echo "Rp. " . $harga['harga'] . " /Kg";
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col s3">
            <div class="card-panel grey lighten-5 z-depth-1">
                <div class="row valign-wrapper">
                    <a href="pesan-laundry.php?id=<?= $idAgen ?>&jenis=setrika" style="margin:0% 15%"><button class="btn blue darken-3">SETRIKA</button></a>
                    <div>
                        <?php
                            $harga = mysqli_query($connect, "SELECT * FROM harga WHERE id_agen = '$idAgen' AND jenis = 'setrika'");
                            $harga = mysqli_fetch_assoc($harga);
                            echo "Rp. " . $harga['harga'] . " /Kg";
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col s3">
            <div class="card-panel grey lighten-5 z-depth-1">
                <div class="row valign-wrapper">
                    <a href="pesan-laundry.php?id=<?= $idAgen ?>&jenis=komplit" style="margin:0% 15%"><button class="btn blue darken-3">KOMPLIT</button></a>
                    <div>
                        <?php
                            $harga = mysqli_query($connect, "SELECT * FROM harga WHERE id_agen = '$idAgen' AND jenis = 'komplit'");
                            $harga = mysqli_fetch_assoc($harga);
                            echo "Rp. " . $harga['harga'] . " /Kg";
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end agen -->


    <hr><br>

    <!-- komentar -->
    <h3 class="header light center">Ulasan Pengguna</h3>
    <br>

    <div class="row">
        <?php
        $temp = mysqli_query($connect, "SELECT * FROM transaksi WHERE id_agen = $idAgen AND rating > 0");
        while ($transaksi = mysqli_fetch_assoc($temp)) :
            $idPelanggan = $transaksi["id_pelanggan"];
            $temp2 = mysqli_query($connect, "SELECT * FROM pelanggan WHERE id_pelanggan = $idPelanggan");
            $pelanggan = mysqli_fetch_assoc($temp2);
        ?>
            <div class="col s12 m4">
                <div class="card rounded small">
                    <div class="card-image">
                        <img src="img/pelanggan/<?= $pelanggan['foto'] ?>" class="circle responsive-img" style="margin: 15px auto;" width="80" alt="foto">
                    </div>
                    <div class="card-content center-align" style="padding: 15px;">
                        <h6 style="font-size: 1rem; margin: 10px 0;"><?= $pelanggan["nama"] ?></h6>
                        <fieldset class="bintang"><span class="starImg star-<?= $transaksi['rating'] ?>"></span></fieldset>
                        <p class="review-text" style="margin: 10px 0;">
                            <?= $transaksi["komentar"] ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <style>
        .card.rounded {
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
            transition: all 0.3s cubic-bezier(.25,.8,.25,1);
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .card.rounded:hover {
            box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
        }
        .review-text {
            white-space: pre-wrap;
            word-wrap: break-word;
            max-height: 80px;
            overflow-y: auto;
            font-size: 0.85em;
            color: #666;
            line-height: 1.4;
        }
        .card-image {
            padding: 8px;
        }
        .card-content {
            flex-grow: 1;
        }
    </style>

    <?php include "footer.php"; ?>

</body>
</html>