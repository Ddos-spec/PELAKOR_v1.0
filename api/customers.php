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
            $phone = $data['phone'];
            
            $stmt = $conn->prepare("INSERT INTO tb_customer (nama_customer, no_telp) VALUES (?, ?)");
            $stmt->bind_param('ss', $name, $phone);
            $stmt->execute();
            
            echo json_encode(['success' => true]);
            break;

        case 'update':
            $id = $data['id'];
            $name = $data['name'];
            $phone = $data['phone'];
            
            $stmt = $conn->prepare("UPDATE tb_customer SET nama_customer = ?, no_telp = ? WHERE id_customer = ?");
            $stmt->bind_param('ssi', $name, $phone, $id);
            $stmt->execute();
            
            echo json_encode(['success' => true]);
            break;

        case 'delete':
            $id = $data['id'];
            
            $stmt = $conn->prepare("DELETE FROM tb_customer WHERE id_customer = ?");
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
