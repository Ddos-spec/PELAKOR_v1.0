<nav class="bg-[#005f99]">
    <div class="container mx-auto">
        <div class="flex justify-between items-center py-4">
            <a id="logo-container" href="index.php" class="text-white text-lg font-bold"><i class="material-icons left">home</i>LaundryKu</a>
            <ul class="flex space-x-4">
                <li> 
                <?php
                    global $connect;

                    if ( isset($_SESSION["login-pelanggan"]) && isset($_SESSION["pelanggan"])){
                        $idPelanggan = $_SESSION["pelanggan"];
                        $data = mysqli_query($connect, "SELECT * FROM pelanggan WHERE id_pelanggan = '$idPelanggan'");
                        $data = mysqli_fetch_assoc($data);
                        $nama = $data["nama"];

                        echo "<a class='text-white' href='pelanggan.php'><b>$nama</b> (Pelanggan)</a>";
                    } else if ( isset($_SESSION["login-agen"]) && isset($_SESSION["agen"])){
                        $id_agen = $_SESSION["agen"];
                        $data = mysqli_query($connect, "SELECT * FROM agen WHERE id_agen = '$id_agen'");
                        $data = mysqli_fetch_assoc($data);
                        $nama = $data["nama_laundry"];

                        echo "<a class='text-white' href='agen.php'><b>$nama</b> (Agen)</a>";
                    } else if ( isset($_SESSION["login-admin"]) && isset($_SESSION["admin"])){
                        echo "<a class='text-white' href='admin.php'><b>Admin</b> (Admin)</a>";
                    } else {
                        echo "<a class='text-white' href='registrasi.php'><b>Registrasi</b></a>";
                    }
                ?>
                </li>
                <li>
                <?php
                    if ( isset($_SESSION["login-pelanggan"]) && isset($_SESSION["pelanggan"]) || isset($_SESSION["login-agen"]) && isset($_SESSION["agen"]) || isset($_SESSION["login-admin"]) && isset($_SESSION["admin"]) ){
                        echo "<a class='text-white' href='logout.php'><b>Logout</b></a>";
                    } else {
                        echo "<a class='text-white' href='login.php'><b>Login</b></a>";
                    }
                ?>                                      
                </li>
            </ul>

            <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons text-white">menu</i></a>
        </div>
    </div>
</nav>
<br/>