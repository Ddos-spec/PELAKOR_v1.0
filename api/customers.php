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
            case 'phone':
                return preg_match('/^[0-9]{10,15}$/', $input);
            default:
                return filter_var($input, FILTER_SANITIZE_STRING);
        }
    }

    switch ($action) {
        case 'add':
            $name = validateInput($data['name'] ?? '', 'string', 100);
            $phone = validateInput($data['phone'] ?? '', 'phone', 15);
            
            if (!$name || !$phone) {
                throw new Exception('Invalid input data');
            }

            $stmt = $conn->prepare("INSERT INTO tb_customer (nama_customer, no_telp) VALUES (?, ?)");
            $stmt->execute([$name, $phone]);
            
            echo json_encode(['success' => true, 'data' => ['id' => $conn->lastInsertId()]]);
            break;

        case 'update':
            $id = validateInput($data['id'] ?? '', 'int');
            $name = validateInput($data['name'] ?? '', 'string', 100);
            $phone = validateInput($data['phone'] ?? '', 'phone', 15);
            
            if (!$id || !$name || !$phone) {
                throw new Exception('Invalid input data');
            }

            $stmt = $conn->prepare("UPDATE tb_customer SET nama_customer = ?, no_telp = ? WHERE id_customer = ?");
            $stmt->execute([$name, $phone, $id]);
            
            echo json_encode(['success' => true]);
            break;

        case 'delete':
            $id = validateInput($data['id'] ?? '', 'int');
            
            if (!$id) {
                throw new Exception('Invalid input data');
            }

            $stmt = $conn->prepare("DELETE FROM tb_customer WHERE id_customer = ?");
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
