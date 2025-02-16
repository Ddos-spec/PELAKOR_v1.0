<?php

// Existing validation functions...

// Order Processing Functions

function processKiloanOrder($connect, $idPelanggan) {
    error_log("Processing kiloan order. POST data: " . print_r($_POST, true));
    
    $alamat = htmlspecialchars($_POST["alamat"]);
    $jenis = htmlspecialchars($_POST["jenis"]);
    $catatan = htmlspecialchars($_POST["catatan"]);
    $estimasi_item = htmlspecialchars($_POST["estimasi_item"]);
    $tgl = date("Y-m-d H:i:s"); 
    $tipe_layanan = "kiloan";
    $idAgen = $_GET["id"];

    mysqli_begin_transaction($connect);
    try {
        $query = "INSERT INTO cucian 
                  (id_agen, id_pelanggan, tgl_mulai, jenis, estimasi_item, alamat, catatan, status_cucian, tipe_layanan) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, 'Penjemputan', ?)";
        
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "iissssss", $idAgen, $idPelanggan, $tgl, $jenis, $estimasi_item, $alamat, $catatan, $tipe_layanan);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error creating order: " . mysqli_error($connect));
        }

        $newOrderId = mysqli_insert_id($connect);
        error_log("Successfully created kiloan order with ID: " . $newOrderId);

        mysqli_commit($connect);
        
        return [
            'status' => 'success',
            'message' => 'Pesanan Berhasil',
            'order_id' => $newOrderId
        ];
    } catch (Exception $e) {
        mysqli_rollback($connect);
        error_log("Error in kiloan order: " . $e->getMessage());
        return [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }
}

function processSatuanOrder($connect, $idPelanggan) {
    error_log("Processing satuan order. POST data: " . print_r($_POST, true));
    
    $alamat = htmlspecialchars($_POST["alamat"]);
    $jenis = htmlspecialchars($_POST["jenis"]);
    $catatan = htmlspecialchars($_POST["catatan"]);
    $tgl = date("Y-m-d H:i:s");
    $tipe_layanan = "satuan";
    $idAgen = $_GET["id"];
    
    mysqli_begin_transaction($connect);
    try {
        $query = "INSERT INTO cucian 
                  (id_agen, id_pelanggan, tgl_mulai, jenis, alamat, catatan, status_cucian, tipe_layanan) 
                  VALUES (?, ?, ?, ?, ?, ?, 'Penjemputan', ?)";
        
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "iisssss", $idAgen, $idPelanggan, $tgl, $jenis, $alamat, $catatan, $tipe_layanan);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error creating order: " . mysqli_error($connect));
        }
        
        $cucian_id = mysqli_insert_id($connect);
        error_log("Created main order with ID: " . $cucian_id);
        
        $total_items = 0;
        
        foreach($_POST["item"] as $id_harga_satuan => $qty) {
            if ($qty > 0) {
                $total_items += $qty;
                
                // Get base price
                $qHarga = mysqli_prepare($connect, "SELECT harga FROM harga_satuan WHERE id_harga_satuan = ?");
                mysqli_stmt_bind_param($qHarga, "i", $id_harga_satuan);
                mysqli_stmt_execute($qHarga);
                $result = mysqli_stmt_get_result($qHarga);
                $baseHarga = mysqli_fetch_assoc($result)['harga'];
                
                // Calculate adjusted price
                $harga = $baseHarga;
                if($jenis === 'setrika') $harga *= 0.8;
                if($jenis === 'komplit') $harga *= 1.5;
                
                $subtotal = $qty * $harga;
                
                error_log("Adding item: Satuan ID $id_harga_satuan, Qty $qty, Price $harga");
                
                $stmt = mysqli_prepare($connect, "INSERT INTO detail_cucian 
                    (id_cucian, id_harga_satuan, jumlah, subtotal) 
                    VALUES (?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "iiid", $cucian_id, $id_harga_satuan, $qty, $subtotal);
                mysqli_stmt_execute($stmt);
            }
        }

        error_log("Updating total items to: " . $total_items);
        $stmt = mysqli_prepare($connect, "UPDATE cucian SET total_item = ? WHERE id_cucian = ?");
        mysqli_stmt_bind_param($stmt, "ii", $total_items, $cucian_id);
        mysqli_stmt_execute($stmt);

        mysqli_commit($connect);
        
        return [
            'status' => 'success',
            'message' => 'Pesanan Berhasil',
            'order_id' => $cucian_id
        ];
    } catch (Exception $e) {
        mysqli_rollback($connect);
        error_log("Error in satuan order: " . $e->getMessage());
        return [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }
}

// Existing session functions...
?>
