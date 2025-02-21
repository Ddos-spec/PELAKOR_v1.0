

<?php


// USERNAME
function validasiUsername($objek){
    if (empty($objek)){
        echo "
            <script>
                Swal.fire('Username Tidak Boleh Kosong','','error');
            </script>
        ";
        exit;
    }else if (!preg_match("/^[a-zA-Z0-9]*$/",$objek)){
        echo "
            <script>
                Swal.fire('Username Hanya Boleh Huruf dan Angka','','error');
            </script>
        ";
        exit;
    }
}

// NO HP
function validasiTelp($objek){
    if (empty($objek)){
        echo "
            <script>
                Swal.fire('No Telp Tidak Boleh Kosong','','error');
            </script>
        ";
        exit;
    }else if (!preg_match("/^[0-9]*$/",$objek)){
        echo "
            <script>
                Swal.fire('No Telp Hanya Diperbolehkan Angka','','error');
            </script>
        ";
        exit;
    }
}

// Berat
function validasiBerat($objek){
    if (empty($objek)){
        echo "
            <script>
                Swal.fire('Form Tidak Boleh Kosong','','error');
            </script>
        ";
        exit;
    }else if (!preg_match("/^[0-9]*$/",$objek)){
        echo "
            <script>
                Swal.fire('Satuan Berat Hanya Diperbolehkan Angka','','error');
            </script>
        ";
        exit;
    }
}

// HARGA
function validasiHarga($objek){
    if (empty($objek)){
        echo "
            <script>
                Swal.fire('Form Harga Tidak Boleh Kosong','','error');
            </script>
        ";
        exit;
    }else if (!preg_match("/^[0-9]*$/",$objek)){
        echo "
            <script>
                Swal.fire('Masukkan Harga Yang Benar !','','error');
            </script>
        ";
        exit;
    }
}

// EMAIL
function validasiEmail($objek){
    if (empty($objek)){
        echo "
            <script>
                Swal.fire('Form Email Tidak Boleh Kosong','','error');
            </script>
        ";
        exit;
    }else if (!filter_var($objek, FILTER_VALIDATE_EMAIL)){
        echo "
            <script>
                Swal.fire('Masukkan Format Email Yang Benar','','error');
            </script>
        ";
        exit;
    }
}

// NAMA ORANG
function validasiNama($objek){
    if (empty($objek)){
        echo "
            <script>
                Swal.fire('Form Nama Tidak Boleh Kosong','','error');
            </script>
        ";
        exit;
    }else if (!preg_match("/^[a-zA-Z .]*$/",$objek)){
        echo "
            <script>
                Swal.fire('Nama Hanya Diperbolehkan Huruf dan Spasi','','error');
            </script>
        ";
        exit;
    }
}






// SESSION

// admin
function cekAdmin(){
    if ( isset($_SESSION["login-admin"]) && isset($_SESSION["admin"]) ){

        $idAdmin = $_SESSION["admin"];
        
    }else {
        echo "
            <script>
                window.location = 'login.php';
            </script>
        ";
        exit;
    }
}


// agen
function cekAgen(){
    if (isset($_SESSION["login-agen"]) && isset($_SESSION["agen"]) ){

        $idAgen = $_SESSION["agen"];
    }else {
        echo "
            <script>
                window.location = 'login.php';
            </script>
        ";
        exit;
    }
}


// pengguna
function cekPelanggan(){
    if ( isset($_SESSION["login-pelanggan"]) && isset($_SESSION["pelanggan"]) ){

        $idPelanggan = $_SESSION["pelanggan"];
    }else {
        echo "
            <script>
                window.location = 'login.php';
            </script>
        ";
        exit;
    }
}


// login
function cekLogin(){
    if ( (isset($_SESSION["login-pelanggan"]) && isset($_SESSION["pelanggan"])) || (isset($_SESSION["login-agen"]) && isset($_SESSION["agen"])) || (isset($_SESSION["login-admin"]) && isset($_SESSION["admin"])) ) {
        echo "
            <script>
                window.location = 'index.php';
            </script>
        ";
        exit;
    }
}

// belum login
function cekBelumLogin(){
    if ( !(isset($_SESSION["login-pelanggan"]) && isset($_SESSION["pelanggan"])) && !(isset($_SESSION["login-agen"]) && isset($_SESSION["agen"])) && !(isset($_SESSION["login-admin"]) && isset($_SESSION["admin"])) ) {
        echo "
            <script>
                window.location = 'login.php';
            </script>
        ";
        exit;
    }
}

function getHargaPaket($jenis, $idAgen) {
    global $connect;
    $jenisEscaped = mysqli_real_escape_string($connect, $jenis);
    $q = mysqli_query($connect, "SELECT harga FROM harga WHERE id_agen = $idAgen AND jenis = '$jenisEscaped'");
    $row = mysqli_fetch_assoc($q);
    return $row['harga'] ?? 0;
}

function getPerItemPrice($item, $idAgen) {
    global $connect;
    $itemEscaped = mysqli_real_escape_string($connect, $item);
    $q = mysqli_query($connect, "SELECT harga FROM harga WHERE id_agen = $idAgen AND jenis = '$itemEscaped'");
    $row = mysqli_fetch_assoc($q);
    return $row['harga'] ?? 0;
}

function getTotalPerItem($itemType, $idAgen) {
    if (empty($itemType)) return 0;
    
    $total = 0;
    $items = explode(', ', $itemType);
    foreach ($items as $it) {
        if (trim($it) === "") continue;
        if (preg_match('/([^(]+)\((\d+)\)/', $it, $matches)) {
            $item = strtolower(trim($matches[1]));
            $qty = (int)$matches[2];
            $price = getPerItemPrice($item, $idAgen);
            $total += $price * $qty;
        }
    }
    return $total;
}

function calculateTotalHarga($transaksi) {
    if (!isset($transaksi['berat']) || !isset($transaksi['jenis']) || !isset($transaksi['id_agen'])) {
        return 0;
    }
    
    $weightPrice = getHargaPaket($transaksi['jenis'], $transaksi['id_agen']) * $transaksi['berat'];
    $itemsPrice = getTotalPerItem($transaksi['item_type'] ?? '', $transaksi['id_agen']);
    
    return $weightPrice + $itemsPrice;
}

?>