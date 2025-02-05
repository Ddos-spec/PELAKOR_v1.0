<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "headtags.html"; ?>
    <title>Data Penggunan - <?= $data["nama"] ?></title>
    <style>
        .profile-img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
        }
        .btn-custom {
            margin: 10px 5px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }
        .btn-custom:hover {
            transform: scale(1.05);
        }
        .btn-save {
            background-color: white;
            color: black;
            border: 1px solid black;
        }
        .btn-change-password {
            background-color: #007bff;
            color: white;
        }
        .btn-logout {
            background-color: #dc3545;
            color: white;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group textarea {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <!-- header -->
    <?php include 'header.php'; ?>
    <!-- end header -->

    <!-- body -->
    <div class="container">
        <div class="row">
            <div class="col s12 m8 offset-m2 l6 offset-l3">
                <h3 class="header light center">DATA PENGGUNA</h3>
                <div class="center">
                    <img src="img/pelanggan/<?= $data['foto'] ?>" class="profile-img" alt="">
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" id="nama" name="nama" value="<?= $data['nama'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email" value="<?= $data['email'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="telp">No Telp</label>
                        <input type="text" id="telp" name="telp" value="<?= $data['telp'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="kota">Kota / Kabupaten</label>
                        <input type="text" id="kota" name="kota" value="<?= $data['kota'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea class="materialize-textarea" id="alamat" name="alamat"><?= $data['alamat'] ?></textarea>
                    </div>
                    <div class="file-field input-field">
                        <div class="btn btn-custom btn-change-password">
                            <span>Foto Profil</span>
                            <input type="file" name="foto" id="foto">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text" placeholder="Upload foto profil">
                        </div>
                    </div>
                    <div class="center">
                        <button class="btn-custom btn-save" type="submit" name="ubah-data">Simpan Data</button>
                        <a class="btn-custom btn-change-password" href="ganti-kata-sandi.php">Ganti Kata Sandi</a>
                        <a class="btn-custom btn-logout" href="logout.php">Logout</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end body -->

    <!-- footer -->
    <?php include "footer.php"; ?>
    <!-- end footer -->

</body>
</html>