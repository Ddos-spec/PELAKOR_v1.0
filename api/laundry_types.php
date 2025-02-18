<?php
session_start();
require_once '../db_connection.php';

// API security settings
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');

// Validate session
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Rate limiting (basic implementation)
if (isset($_SESSION['last_api_request']) && 
    time() - $_SESSION['last_api_request'] < 1) {
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Too many requests']);
    exit();
}
$_SESSION['last_api_request'] = time();

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? '';

    // Validate and sanitize input
    function validateInput($input, $type = 'string', $maxLength = 255) {
        $input = trim($input);
        if (empty($input)) return false;
        if (strlen($input) > $maxLength) return false;
        
        switch ($type) {
            case 'int':
                return filter_var($input, FILTER_VALIDATE_INT);
            case 'float':
                return filter_var($input, FILTER_VALIDATE_FLOAT);
            default:
                return filter_var($input, FILTER_SANITIZE_STRING);
        }
    }

    switch ($action) {
        case 'add':
            $name = validateInput($data['name'] ?? '', 'string', 100);
            $price = validateInput($data['price'] ?? '', 'float');
            
            if (!$name || !$price) {
                throw new Exception('Invalid input data');
            }

            $stmt = $conn->prepare("INSERT INTO tb_jenis_cucian (nama_jenis_cucian, harga_satuan) VALUES (?, ?)");
            $stmt->execute([$name, $price]);
            
            echo json_encode(['success' => true, 'data' => ['id' => $conn->lastInsertId()]]);
            break;

        case 'update':
            $id = validateInput($data['id'] ?? '', 'int');
            $name = validateInput($data['name'] ?? '', 'string', 100);
            $price = validateInput($data['price'] ?? '', 'float');
            
            if (!$id || !$name || !$price) {
                throw new Exception('Invalid input data');
            }

            $stmt = $conn->prepare("UPDATE tb_jenis_cucian SET nama_jenis_cucian = ?, harga_satuan = ? WHERE id_jenis_cucian = ?");
            $stmt->execute([$name, $price, $id]);
            
            echo json_encode(['success' => true]);
            break;

        case 'delete':
            $id = validateInput($data['id'] ?? '', 'int');
            
            if (!$id) {
                throw new Exception('Invalid input data');
            }

            $stmt = $conn->prepare("DELETE FROM tb_jenis_cucian WHERE id_jenis_cucian = ?");
            $stmt->execute([$id]);
            
            echo json_encode(['success' => true]);
            break;

        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            break;
    }
} catch (PDOException $e) {
    error_log('API Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
