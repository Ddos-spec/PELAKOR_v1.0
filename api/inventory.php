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
            $stock = $data['stock'];
            
            $stmt = $conn->prepare("INSERT INTO tb_inventory (nama_inventory, stok) VALUES (?, ?)");
            $stmt->bind_param('si', $name, $stock);
            $stmt->execute();
            
            echo json_encode(['success' => true]);
            break;

        case 'update':
            $id = $data['id'];
            $name = $data['name'];
            $stock = $data['stock'];
            
            $stmt = $conn->prepare("UPDATE tb_inventory SET nama_inventory = ?, stok = ? WHERE id_inventory = ?");
            $stmt->bind_param('sii', $name, $stock, $id);
            $stmt->execute();
            
            echo json_encode(['success' => true]);
            break;

        case 'delete':
            $id = $data['id'];
            
            $stmt = $conn->prepare("DELETE FROM tb_inventory WHERE id_inventory = ?");
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
