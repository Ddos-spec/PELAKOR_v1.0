

<?php


// USERNAME
function validasiUsername($objek) {
    $errors = [];
    if (empty($objek)) {
        $errors[] = 'Username Tidak Boleh Kosong';
    } else if (!preg_match("/^[a-zA-Z0-9]*$/", $objek)) {
        $errors[] = 'Username Hanya Boleh Huruf dan Angka';
    }
    return $errors;
}

function validasiTelp($objek) {
    $errors = [];
    if (empty($objek)) {
        $errors[] = 'No Telp Tidak Boleh Kosong';
    } else if (!preg_match("/^[0-9]*$/", $objek)) {
        $errors[] = 'No Telp Hanya Diperbolehkan Angka';
    }
    return $errors;
}

function validasiBerat($objek) {
    $errors = [];
    if (empty($objek)) {
        $errors[] = 'Form Tidak Boleh Kosong';
    } else if (!preg_match("/^[0-9]*$/", $objek)) {
        $errors[] = 'Satuan Berat Hanya Diperbolehkan Angka';
    }
    return $errors;
}

function validasiHarga($objek) {
    $errors = [];
    if (empty($objek)) {
        $errors[] = 'Form Harga Tidak Boleh Kosong';
    } else if (!preg_match("/^[0-9]*$/", $objek)) {
        $errors[] = 'Masukkan Harga Yang Benar !';
    }
    return $errors;
}

function validasiEmail($objek) {
    $errors = [];
    if (empty($objek)) {
        $errors[] = 'Form Email Tidak Boleh Kosong';
    } else if (!filter_var($objek, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Masukkan Format Email Yang Benar';
    }
    return $errors;
}

function validasiNama($objek) {
    $errors = [];
    if (empty($objek)) {
        $errors[] = 'Form Nama Tidak Boleh Kosong';
    } else if (!preg_match("/^[a-zA-Z .]*$/", $objek)) {
        $errors[] = 'Nama Hanya Diperbolehkan Huruf dan Spasi';
    }
    return $errors;
}
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


?>