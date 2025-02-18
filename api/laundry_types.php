<?php
session_start();
require_once '../db_connection.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? '';

try {
    switch ($action) {
        case 'add':
            $name = $data['name'];
            $price = $data['price'];
            
            $stmt = $conn->prepare("INSERT INTO tb_jenis_cucian (nama_jenis_cucian, harga_satuan) VALUES (?, ?)");
            $stmt->bind_param('si', $name, $price);
            $stmt->execute();
            
            echo json_encode(['success' => true]);
            break;

        case 'update':
            $id = $data['id'];
            $name = $data['name'];
            $price = $data['price'];
            
            $stmt = $conn->prepare("UPDATE tb_jenis_cucian SET nama_jenis_cucian = ?, harga_satuan = ? WHERE id_jenis_cucian = ?");
            $stmt->bind_param('sii', $name, $price, $id);
            $stmt->execute();
            
            echo json_encode(['success' => true]);
            break;

        case 'delete':
            $id = $data['id'];
            
            $stmt = $conn->prepare("DELETE FROM tb_jenis_cucian WHERE id_jenis_cucian = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            
            echo json_encode(['success' => true]);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            break;
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
