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
                        // Improved rating calculation
                        $temp = $agen["id_agen"];
                        $queryStar = mysqli_query($connect,"SELECT AVG(rating) as avg_rating, COUNT(*) as total_ratings 
                                                          FROM transaksi 
                                                          WHERE id_agen = '$temp' AND rating > 0");
                        $ratingData = mysqli_fetch_assoc($queryStar);

                        $fixStar = $ratingData['avg_rating'] ? ceil($ratingData['avg_rating']) : 0;
                        $totalRatings = $ratingData['total_ratings'];
                    ?>

                    <div class="rating-section">
                        <fieldset class="bintang">
                            <span class="starImg star-<?= $fixStar ?>"></span>
                        </fieldset>
                        <?php if($totalRatings > 0): ?>
                            <div class="rating-info">
                                <span class="rating-number"><?= number_format($ratingData['avg_rating'], 1) ?></span>
                                <span class="rating-count">(<?= $totalRatings ?> ulasan)</span>
                            </div>
                        <?php else: ?>
                            <div class="rating-info">
                                <span class="grey-text">Belum ada ulasan</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </li>
                <li>Alamat : <?= $agen["alamat"] . ", " . $agen["kota"] ?></li>
                <li>No. HP : <?= $agen["telp"] ?></li>
            </ul>
        </div>
    </div>

    <hr><br>

    <!-- komentar -->
    <h3 class="header light center">Ulasan Pengguna</h3>
    <br>

    <div class="row">
        <?php
        $temp = mysqli_query($connect, "SELECT * FROM transaksi WHERE id_agen = $idAgen");
        while ( $transaksi = mysqli_fetch_assoc($temp) ) :
        
        $idPelanggan = $transaksi["id_pelanggan"];
        $temp2 = mysqli_query($connect, "SELECT * FROM pelanggan WHERE id_pelanggan = $idPelanggan");
        $pelanggan = mysqli_fetch_assoc($temp2);
        ?>

        <div class="container">
            <div class="col s3 offset-s1">
                <table border=0>
                    <tr>
                        <td width=100px rowspan=3><img src="img/pelanggan/<?= $pelanggan['foto'] ?>" class="circle responsive-img" width=100px alt="foto"></td>
                        <td><?= "<h6 class='light'>" . $pelanggan["nama"] . "</h6>";?></td>
                    </tr>
                    <tr>
                        <td><fieldset class="bintang"><span class="starImg star-<?= $transaksi['rating'] ?>"></span></fieldset></td>
                    </tr>
                    <tr>
                        <td><?= $transaksi["komentar"]; ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

    <?php include "footer.php"; ?>
</body>
</html>