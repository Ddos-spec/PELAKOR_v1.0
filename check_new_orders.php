<?php
include 'connect-db.php';

$id_agen = $_GET['id_agen'];
$last_check = $_GET['last_check'] ?? date('Y-m-d H:i:s', strtotime('-5 minutes'));

$query = mysqli_query($connect, "SELECT COUNT(*) as new_orders 
                               FROM cucian 
                               WHERE id_agen = '$id_agen' 
                               AND status_cucian = 'Penjemputan'
                               AND tgl_mulai > '$last_check'");

$result = mysqli_fetch_assoc($query);

header('Content-Type: application/json');
echo json_encode([
    'new_orders' => $result['new_orders'],
    'last_check' => date('Y-m-d H:i:s')
]);
