<?php
session_start();
require_once 'connect-db.php';
require_once 'functions/functions.php';

// Function to get package price
function getHargaPaket($jenis, $idAgen) {
    global $connect;
    $jenisEscaped = mysqli_real_escape_string($connect, $jenis);
    $q = mysqli_query($connect, "SELECT harga FROM harga WHERE id_agen = $idAgen AND jenis = '$jenisEscaped'");
    $row = mysqli_fetch_assoc($q);
    return $row['harga'] ?? 0;
}

// Function to get item price
function getPerItemPrice($item, $idAgen) {
    global $connect;
    $itemEscaped = mysqli_real_escape_string($connect, $item);
    $q = mysqli_query($connect, "SELECT harga FROM harga WHERE id_agen = $idAgen AND jenis = '$itemEscaped'");
    $row = mysqli_fetch_assoc($q);
    return $row['harga'] ?? 0;
}

// Function to calculate total per item
function getTotalPerItem($itemType, $idAgen) {
    $total = 0;
    $items = explode(', ', $itemType);
    foreach ($items as $it) {
        if (trim($it) === "") continue;
        if (preg_match('/([^(]+)\((\d+)\)/', $it, $matches)) {
            $item = strtolower(trim($matches[1]));
            $qty = (int)$matches[2];
            $price = getPerItemPrice($item,
