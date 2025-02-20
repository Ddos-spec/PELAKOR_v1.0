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
    <div class="container">
        <h4 class="header light center">Ulasan Pengguna</h4>
        <div class="row">
            <?php
            $temp = mysqli_query($connect, "SELECT * FROM transaksi WHERE id_agen = $idAgen");
            while ($transaksi = mysqli_fetch_assoc($temp)):
                $idPelanggan = $transaksi["id_pelanggan"];
                $temp2 = mysqli_query($connect, "SELECT * FROM pelanggan WHERE id_pelanggan = $idPelanggan");
                $pelanggan = mysqli_fetch_assoc($temp2);
            ?>
            <div class="col s12 m4">
                <div class="card-panel grey lighten-5" style="padding: 12px; margin: 8px;">
                    <div class="row valign-wrapper" style="margin-bottom: 0;">
                        <div class="col s4">
                            <img src="img/pelanggan/<?= $pelanggan['foto'] ?>" class="circle responsive-img" alt="foto pengguna">
                        </div>
                        <div class="col s8">
                            <span class="black-text">
                                <strong><?= $pelanggan["nama"] ?></strong>
                                <div class="rating-container">
                                    <fieldset class="bintang"><span class="starImg star-<?= $transaksi['rating'] ?>"></span></fieldset>
                                </div>
                                <p class="grey-text text-darken-1 comment-text">
                                    <?= $transaksi["komentar"]; ?>
                                </p>
                                <?php 
                                // Show delete button only if logged in as this agent
                                if (isset($_SESSION['agen']) && $_SESSION['agen'] == $idAgen): 
                                    // Generate CSRF token if not exists
                                    if (!isset($_SESSION['csrf_token'])) {
                                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                                    }
                                ?>
                                <form action="delete-review.php" method="POST" style="margin-top: 10px;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?')">
                                    <input type="hidden" name="id_transaksi" value="<?= $transaksi['id_transaksi'] ?>">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <button type="submit" class="btn red darken-2 btn-small">Hapus Ulasan</button>
                                </form>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <style>
        .card-panel {
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1) !important;
        }
        
        .rating-container {
            margin-top: 3px;
        }
        
        .valign-wrapper img.circle {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
        
        .comment-text {
            font-size: 0.9rem;
            margin-top: 3px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }
        
        @media only screen and (max-width: 600px) {
            .card-panel {
                padding: 10px !important;
            }
            
            .valign-wrapper img.circle {
                width: 45px;
                height: 45px;
            }
            
            .comment-text {
                font-size: 0.85rem;
            }
        }
        
        /* Untuk layar medium */
        @media only screen and (min-width: 601px) and (max-width: 992px) {
            .container {
                width: 95%;
            }
        }
    </style>

    <?php include "footer.php"; ?>

</body>
</html>