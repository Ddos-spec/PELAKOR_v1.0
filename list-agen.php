<?php

session_start();
include 'connect-db.php';
include 'functions/functions.php';

// validasi login
cekAdmin();

//konfirgurasi pagination
$jumlahDataPerHalaman = 5;
$query = mysqli_query($connect,"SELECT * FROM agen");
$jumlahData = mysqli_num_rows($query);
//ceil() = pembulatan ke atas
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);

//menentukan halaman aktif
//$halamanAktif = ( isset($_GET["page"]) ) ? $_GET["page"] : 1; = versi simple
if ( isset($_GET["page"])){
    $halamanAktif = $_GET["page"];
}else{
    $halamanAktif = 1;
}

//data awal
$awalData = ( $jumlahDataPerHalaman * $halamanAktif ) - $jumlahDataPerHalaman;

//fungsi memasukkan data di db ke array
$agen = mysqli_query($connect,"SELECT * FROM agen ORDER BY id_agen DESC LIMIT $awalData, $jumlahDataPerHalaman");


//ketika tombol cari ditekan
if ( isset($_POST["cari"])) {
    $keyword = htmlspecialchars($_POST["keyword"]);

    $query = "SELECT * FROM agen WHERE 
        nama_laundry LIKE '%$keyword%' OR
        nama_pemilik LIKE '%$keyword%' OR
        kota LIKE '%$keyword%' OR
        email LIKE '%$keyword%' OR
        alamat LIKE '%$keyword%'
        ORDER BY id_agen DESC
        LIMIT $awalData, $jumlahDataPerHalaman
        ";

    $agen = mysqli_query($connect,$query);

    //konfirgurasi pagination
    $jumlahDataPerHalaman = 3;
    $jumlahData = mysqli_num_rows($agen);
    //ceil() = pembulatan ke atas
    $jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);

    //menentukan halaman aktif
    //$halamanAktif = ( isset($_GET["page"]) ) ? $_GET["page"] : 1; = versi simple
    if ( isset($_GET["page"])){
        $halamanAktif = $_GET["page"];
    }else{
        $halamanAktif = 1;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "headtags.html"; ?>
    <title>Data Agen</title>
</head>
<body>

    <!-- header -->
    <?php include 'header.php'; ?>
    <!-- end header -->


    <h3 class="header light center">List Agen</h3>
    <br>


    <!-- searching -->
    <form class="col s12 center" action="" method="post">
        <div class="input-field inline">
            <input type="text" size=40 name="keyword" placeholder="Keyword">
            <button type="submit" class="btn waves-effect blue darken-2" name="cari"><i class="material-icons">send</i></button>
        </div>
    </form>
    <!-- end searching -->

    <!-- Filter Section -->
    <div class="row">
        <div class="col s12">
            <div class="input-field col s3">
                <input type="text" id="searchInput" onkeyup="filterAgen()">
                <label for="searchInput">Cari Agen</label>
            </div>
            <div class="input-field col s3">
                <select id="filterKota" onchange="filterAgen()">
                    <option value="">Semua Kota</option>
                    <?php
                    $kotas = mysqli_query($connect, "SELECT DISTINCT kota FROM agen ORDER BY kota");
                    while($kota = mysqli_fetch_assoc($kotas)) {
                        echo "<option value='{$kota['kota']}'>{$kota['kota']}</option>";
                    }
                    ?>
                </select>
                <label>Filter Kota</label>
            </div>
            <div class="input-field col s3">
                <select id="filterRating" onchange="filterAgen()">
                    <option value="">Semua Rating</option>
                    <option value="4">4+ ⭐</option>
                    <option value="3">3+ ⭐</option>
                    <option value="2">2+ ⭐</option>
                    <option value="1">1+ ⭐</option>
                </select>
                <label>Filter Rating</label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col s10 offset-s1">
            
            <!-- pagination -->
            <ul class="pagination center">
            <?php if( $halamanAktif > 1 ) : ?>
                <li class="disabled-effect blue darken-1">
                    <!-- halaman pertama -->
                    <a href="?page=<?= $halamanAktif - 1; ?>"><i class="material-icons">chevron_left</i></a>
                </li>
            <?php endif; ?>
            <?php for( $i = 1; $i <= $jumlahHalaman; $i++ ) : ?>
                <?php if( $i == $halamanAktif ) : ?>
                    <li class="active grey"><a href="?page=<?= $i; ?>"><?= $i ?></a></li>
                <?php else : ?>
                    <li class="waves-effect blue darken-1"><a href="?page=<?= $i; ?>"><?= $i ?></a></li>
                <?php endif; ?>
            <?php endfor; ?>
            <?php if( $halamanAktif < $jumlahHalaman ) : ?>
                <li class="waves-effect blue darken-1">
                    <a class="page-link" href="?page=<?= $halamanAktif + 1; ?>"><i class="material-icons">chevron_right</i></a>
                </li>
            <?php endif; ?>
            </ul>
            <!-- pagination -->

        
        
            <!-- data agen -->
            
            <table cellpadding=10 border=1>
                <tr>
                    <th>ID Agen</th>
                    <th>Nama Laundry</th>
                    <th>Nama Pemilik</th>
                    <th>No Telp</th>
                    <th>Email</th>
                    <th>Plat Driver</th>
                    <th>Kota</th>
                    <th>Alamat Lengkap</th>
                    <th>Aksi</th>
                </tr>

                <?php foreach ($agen as $dataAgen) : ?>
                
                <tr class="agen-item" data-kota="<?= $dataAgen['kota'] ?>" data-rating="<?= $rating ?>">
                    <td><?= $dataAgen["id_agen"] ?></td>
                    <td class="nama-laundry"><?= $dataAgen["nama_laundry"] ?></td>
                    <td><?= $dataAgen["nama_pemilik"] ?></td>
                    <td><?= $dataAgen["telp"] ?></td>
                    <td><?= $dataAgen["email"] ?></td>
                    <td><?= $dataAgen["plat_driver"] ?></td>
                    <td><?= $dataAgen["kota"] ?></td>
                    <td><?= $dataAgen["alamat"] ?></td>
                    <td><a class="btn red darken-2" href="list-agen.php?hapus=<?= $dataAgen['id_agen'] ?>" onclick="return confirm('Apakah anda yakin ingin menghapus data ?')"><i class="material-icons">delete</i></a></td>
                </tr>

                <?php endforeach ?>
            </table>
            <!-- end data agen -->



        </div>
        </div>
        
    </div>

    <!-- footer -->
    <?php include "footer.php"; ?>
    <!-- end footer -->

    <script>
    function filterAgen() {
        let search = $('#searchInput').val().toLowerCase();
        let kota = $('#filterKota').val();
        let rating = $('#filterRating').val();

        $('.agen-item').each(function() {
            let item = $(this);
            let namaAgen = item.find('.nama-laundry').text().toLowerCase();
            let kotaAgen = item.data('kota');
            let ratingAgen = parseFloat(item.data('rating'));
            
            let showItem = true;

            if(search && !namaAgen.includes(search)) showItem = false;
            if(kota && kotaAgen !== kota) showItem = false;
            if(rating && ratingAgen < rating) showItem = false;

            item.toggle(showItem);
        });
    }

    $(document).ready(function() {
        $('select').formSelect();
    });
    </script>
</body>
</html>
<?php

if (isset($_GET["hapus"])){

    // ambil id agen dri method post
    $idAgen = $_GET["hapus"];

    // hapus data agen
    $query = mysqli_query($connect, "DELETE FROM agen WHERE id_agen = '$idAgen'");

    // kalau berhasil di hapus, keluar alert
    if ( mysqli_affected_rows($connect) > 0 ){
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