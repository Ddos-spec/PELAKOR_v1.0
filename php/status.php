<?php

session_start();
include 'connect-db.php';
include 'functions/functions.php';

cekBelumLogin();

// Sesuaikan dengan jenis login
if (isset($_SESSION["login-admin"]) && isset($_SESSION["admin"])) {
    $login = "Admin";
    $idAdmin = $_SESSION["admin"];
} else if (isset($_SESSION["login-agen"]) && isset($_SESSION["agen"])) {
    $idAgen = $_SESSION["agen"];
    $login = "Agen";
} else if (isset($_SESSION["login-pelanggan"]) && isset($_SESSION["pelanggan"])) {
    $idPelanggan = $_SESSION["pelanggan"];
    $login = "Pelanggan";
} else {
    echo "
        <script>
            window.location = 'login.php';
        </script>
    ";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "headtags.html" ?>
    <title>Status Cucian - <?= $login ?></title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="uk-container uk-margin-top">
        <h3 class="uk-heading-line uk-text-center"><span>Status Cucian</span></h3>
        <br>
        <?php if ($login == "Admin") : 
            $query = mysqli_query($connect, "SELECT * FROM cucian WHERE status_cucian != 'Selesai'");
            if (!$query) {
                echo "<script>Swal.fire('Error', 'Failed to retrieve data', 'error');</script>";
                exit;
            }
        ?>
        <div class="uk-overflow-auto">
            <table class="uk-table uk-table-divider uk-table-hover uk-table-responsive">
                <thead>
                    <tr>
                        <th>ID Cucian</th>
                        <th>Nama Agen</th>
                        <th>Pelanggan</th>
                        <th>Total Item</th>
                        <th>Berat (kg)</th>
                        <th>Jenis</th>
                        <th>Tanggal Dibuat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($cucian = mysqli_fetch_assoc($query)) : ?>
                    <tr>
                        <td><?= $idCucian = $cucian['id_cucian']; ?></td>
                        <td>
                            <?php
                                $data = mysqli_query($connect, "SELECT agen.nama_laundry FROM cucian INNER JOIN agen ON agen.id_agen = cucian.id_agen WHERE id_cucian = $idCucian");
                                $data = mysqli_fetch_assoc($data);
                                echo $data["nama_laundry"];
                            ?>
                        </td>
                        <td>
                            <?php
                                $data = mysqli_query($connect, "SELECT pelanggan.nama FROM cucian INNER JOIN pelanggan ON pelanggan.id_pelanggan = cucian.id_pelanggan WHERE id_cucian = $idCucian");
                                $data = mysqli_fetch_assoc($data);
                                echo $data["nama"];
                            ?>
                        </td>
                        <td><?= $cucian["total_item"] ?></td>
                        <td><?= $cucian["berat"] ?></td>
                        <td><?= $cucian["jenis"] ?></td>
                        <td><?= $cucian["tgl_mulai"] ?></td>
                        <td><?= $cucian["status_cucian"] ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php elseif ($login == "Agen") : 
            $query = mysqli_query($connect, "SELECT * FROM cucian WHERE id_agen = $idAgen AND status_cucian != 'Selesai'");
            if (!$query) {
                echo "<script>Swal.fire('Error', 'Failed to retrieve data', 'error');</script>";
                exit;
            }
        ?>
        <div class="uk-overflow-auto">
            <table class="uk-table uk-table-divider uk-table-hover uk-table-responsive">
                <thead>
                    <tr>
                        <th>ID Cucian</th>
                        <th>Pelanggan</th>
                        <th>Total Item</th>
                        <th>Berat (kg)</th>
                        <th>Jenis</th>
                        <th>Tanggal Dibuat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($cucian = mysqli_fetch_assoc($query)) : ?>
                    <tr>
                        <td><?= $idCucian = $cucian['id_cucian']; ?></td>
                        <td>
                            <?php
                                $data = mysqli_query($connect, "SELECT pelanggan.nama FROM cucian INNER JOIN pelanggan ON pelanggan.id_pelanggan = cucian.id_pelanggan WHERE id_cucian = $idCucian");
                                $data = mysqli_fetch_assoc($data);
                                echo $data["nama"];
                            ?>
                        </td>
                        <td><?= $cucian["total_item"] ?></td>
                        <td>
                            <?php if ($cucian["berat"] == NULL) : ?>
                                <form action="" method="post">
                                    <input type="hidden" name="id_cucian" value="<?= $idCucian ?>">
                                    <div class="uk-form-controls">
                                        <input class="uk-input" type="text" name="berat">
                                        <button class="uk-button uk-button-primary uk-margin-small-top" type="submit" name="simpanBerat">Simpan Berat</button>
                                    </div>
                                </form>
                            <?php else : echo $cucian["berat"]; endif; ?>
                        </td>
                        <td><?= $cucian["jenis"] ?></td>
                        <td><?= $cucian["tgl_mulai"] ?></td>
                        <td><?= $cucian["status_cucian"] ?></td>
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="id_cucian" value="<?= $idCucian ?>">
                                <select class="uk-select" name="status_cucian">
                                    <option disabled selected>Status :</option>
                                    <option value="Penjemputan">Penjemputan</option>
                                    <option value="Sedang di Cuci">Sedang di Cuci</option>
                                    <option value="Sedang Di Jemur">Sedang Di Jemur</option>
                                    <option value="Sedang di Setrika">Sedang di Setrika</option>
                                    <option value="Pengantaran">Pengantaran</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                                <button class="uk-button uk-button-primary uk-margin-small-top" type="submit" name="simpanStatus">Simpan Status</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php elseif ($login == "Pelanggan") : 
            $query = mysqli_query($connect, "SELECT * FROM cucian WHERE id_pelanggan = $idPelanggan AND status_cucian != 'Selesai'");
            if (!$query) {
                echo "<script>Swal.fire('Error', 'Failed to retrieve data', 'error');</script>";
                exit;
            }
        ?>
        <div class="uk-overflow-auto">
            <table class="uk-table uk-table-divider uk-table-hover uk-table-responsive">
                <thead>
                    <tr>
                        <th>ID Cucian</th>
                        <th>Agen</th>
                        <th>Total Item</th>
                        <th>Berat (kg)</th>
                        <th>Jenis</th>
                        <th>Tanggal Dibuat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($cucian = mysqli_fetch_assoc($query)) : ?>
                    <tr>
                        <td><?= $idCucian = $cucian['id_cucian']; ?></td>
                        <td>
                            <?php
                                $data = mysqli_query($connect, "SELECT agen.nama_laundry FROM cucian INNER JOIN agen ON agen.id_agen = cucian.id_agen WHERE id_cucian = $idCucian");
                                $data = mysqli_fetch_assoc($data);
                                echo $data["nama_laundry"];
                            ?>
                        </td>
                        <td><?= $cucian["total_item"] ?></td>
                        <td><?= $cucian["berat"] ?></td>
                        <td><?= $cucian["jenis"] ?></td>
                        <td><?= $cucian["tgl_mulai"] ?></td>
                        <td><?= $cucian["status_cucian"] ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
    <?php include "footer.php"; ?>
    <script src="../node_modules/uikit/dist/js/uikit.min.js"></script>
    <script src="../node_modules/uikit/dist/js/uikit-icons.min.js"></script>
</body>
</html>

<?php

// Status Cucian
if (isset($_POST["simpanStatus"])) {
    $statusCucian = $_POST["status_cucian"];
    $idCucian = $_POST["id_cucian"];

    $query = mysqli_query($connect, "SELECT * FROM cucian INNER JOIN harga ON harga.jenis = cucian.jenis WHERE id_cucian = $idCucian");
    if (!$query) {
        echo "<script>Swal.fire('Error', 'Failed to retrieve data', 'error');</script>";
        exit;
    }
    $cucian = mysqli_fetch_assoc($query);
    if ($statusCucian == "Selesai") {
        $tglMulai = $cucian["tgl_mulai"];
        $tglSelesai = date("Y-m-d H:i:s");
        $totalBayar = $cucian["berat"] * $cucian["harga"];
        $idPelanggan = $cucian["id_pelanggan"];

        $insert_query = mysqli_query($connect, "INSERT INTO transaksi (id_cucian, id_agen, id_pelanggan, tgl_mulai, tgl_selesai, total_bayar, rating) VALUES ($idCucian, $idAgen, $idPelanggan, '$tglMulai', '$tglSelesai', $totalBayar, 0)");
        if (!$insert_query) {
            echo "<script>Swal.fire('Error', 'Failed to insert transaction', 'error');</script>";
            exit;
        }

        mysqli_query($connect, "UPDATE cucian SET status_cucian = '$statusCucian' WHERE id_cucian = $idCucian");
        if (mysqli_affected_rows($connect) > 0) {
            echo "
                <script>
                    Swal.fire('Status Berhasil Di Ubah', '', 'success').then(function() {
                        window.location = 'status.php';
                    });
                </script>
            ";
        }
    }
}

// Total Berat
if (isset($_POST["simpanBerat"])) {
    $berat = htmlspecialchars($_POST["berat"]);
    $idCucian = $_POST["id_cucian"];

    validasiBerat($berat);

    mysqli_query($connect, "UPDATE cucian SET berat = $berat WHERE id_cucian = $idCucian");

    if (mysqli_affected_rows($connect) > 0) {
        echo "
            <script>
                Swal.fire('Data Berhasil Di Ubah', '', 'success').then(function() {
                    window.location = 'status.php';
                });
            </script>
        ";
    }
}

?>