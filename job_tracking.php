<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch job tracking data with additional details
$query = "SELECT jt.id, t.id_transaksi, t.tgl_transaksi, jt.status, 
                 jt.weight, jt.estimated_pickup_date, jt.actual_pickup_date,
                 c.nama_customer, p.nama_petugas
          FROM job_tracking jt
          JOIN tb_transaksi t ON jt.id_transaksi = t.id_transaksi
          LEFT JOIN tb_customer c ON t.id_customer = c.id_customer
          LEFT JOIN tb_petugas p ON t.id_petugas = p.id_petugas";
$result = $conn->query($query);

// Check for unfinished jobs
$unfinishedQuery = "SELECT COUNT(*) as unfinished_count 
                    FROM job_tracking 
                    WHERE status != 'finished' 
                      AND estimated_pickup_date <= CURDATE() 
                      AND notification_sent = 0";
$unfinishedResult = $conn->query($unfinishedQuery);
$unfinishedCount = $unfinishedResult->fetch_assoc()['unfinished_count'];

if ($unfinishedCount > 0) {
    // Mark notifications as sent
    $updateQuery = "UPDATE job_tracking 
                    SET notification_sent = 1 
                    WHERE status != 'finished' 
                      AND estimated_pickup_date <= CURDATE() 
                      AND notification_sent = 0";
    $conn->query($updateQuery);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Tracking - LaundryKu</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-4">
        <h1>Job Tracking</h1>
        
        <?php if ($unfinishedCount > 0): ?>
        <div class="alert alert-warning">
            Warning: <?= $unfinishedCount ?> jobs are not finished but past their estimated pickup date!
        </div>
        <?php endif; ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Job ID</th>
                    <th>Transaction ID</th>
                    <th>Customer</th>
                    <th>Officer</th>
                    <th>Date</th>
                    <th>Weight (kg)</th>
                    <th>Status</th>
                    <th>Est. Pickup</th>
                    <th>Actual Pickup</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['id_transaksi']) ?></td>
                    <td><?= htmlspecialchars($row['nama_customer']) ?></td>
                    <td><?= htmlspecialchars($row['nama_petugas']) ?></td>
                    <td><?= htmlspecialchars($row['tgl_transaksi']) ?></td>
                    <td><?= htmlspecialchars($row['weight']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td><?= htmlspecialchars($row['estimated_pickup_date']) ?></td>
                    <td><?= htmlspecialchars($row['actual_pickup_date']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
