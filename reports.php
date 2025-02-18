<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - LaundryKu</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-4">
        <h1>Reports</h1>
        
        <div class="row">
            <div class="col-md-6">
                <h3>Financial Report</h3>
                <div class="mb-3">
                    <button class="btn btn-primary" onclick="window.print()">Print Report</button>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Income</th>
                            <th>Expenditure</th>
                            <th>Net</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch financial data
                        $query = "SELECT 
                                    DATE(t.tgl_transaksi) AS report_date,
                                    SUM(t.total_price) AS income,
                                    COALESCE(SUM(i.purchase_cost), 0) AS expenditure,
                                    SUM(t.total_price) - COALESCE(SUM(i.purchase_cost), 0) AS net
                                  FROM tb_transaksi t
                                  LEFT JOIN inventory i ON DATE(i.last_restock) = DATE(t.tgl_transaksi)
                                  GROUP BY DATE(t.tgl_transaksi)
                                  ORDER BY report_date DESC";
                        $result = $conn->query($query);
                        
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)):
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($row['report_date']) ?></td>
                            <td><?= number_format($row['income'], 2) ?></td>
                            <td><?= number_format($row['expenditure'], 2) ?></td>
                            <td><?= number_format($row['net'], 2) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <h3>Work Report</h3>
                <div class="mb-3">
                    <button class="btn btn-primary" onclick="window.print()">Print Report</button>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Staff</th>
                            <th>Total Transactions</th>
                            <th>Total Weight (kg)</th>
                            <th>Customers Served</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch work data
                        $query = "SELECT 
                                    p.nama_petugas,
                                    COUNT(t.id_transaksi) AS total_transactions,
                                    COALESCE(SUM(jt.weight), 0) AS total_weight,
                                    COUNT(DISTINCT t.id_customer) AS customers_served
                                  FROM tb_petugas p
                                  LEFT JOIN tb_transaksi t ON p.id_petugas = t.id_petugas
                                  LEFT JOIN job_tracking jt ON t.id_transaksi = jt.id_transaksi
                                  GROUP BY p.id_petugas";
                        $result = $conn->query($query);
                        
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)):
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nama_petugas']) ?></td>
                            <td><?= htmlspecialchars($row['total_transactions']) ?></td>
                            <td><?= number_format($row['total_weight'], 2) ?></td>
                            <td><?= htmlspecialchars($row['customers_served']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
