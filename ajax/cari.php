<?php
session_start();
include '../connect-db.php';

if(isset($_GET['action'])) {
    $action = $_GET['action'];
    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

    if($action === 'searchAgen') {
        // Query untuk pencarian agen
        $query = "SELECT * FROM agen WHERE 
            nama_laundry LIKE '%$keyword%' OR
            nama_pemilik LIKE '%$keyword%' OR
            kota LIKE '%$keyword%' OR
            email LIKE '%$keyword%' OR
            alamat LIKE '%$keyword%'
            ORDER BY id_agen DESC";
        
        $result = mysqli_query($connect, $query);
        $data = [];
        
        while($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        
        echo json_encode($data);
    }
    else if($action === 'searchPelanggan') {
        // Query untuk pencarian pelanggan
        $query = "SELECT * FROM pelanggan WHERE 
            nama LIKE '%$keyword%' OR
            kota LIKE '%$keyword%' OR
            email LIKE '%$keyword%' OR
            alamat LIKE '%$keyword%'
            ORDER BY id_pelanggan DESC";
        
        $result = mysqli_query($connect, $query);
        $data = [];
        
        while($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        
        echo json_encode($data);
    }
}
?>