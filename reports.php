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
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Income</th>
                            <th>Expenditure</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch financial data
                        $query = "SELECT tgl_transaksi, SUM(total_harga) AS income FROM tb_transaksi GROUP BY tgl_transaksi";
                        $result = $conn->query($query);
                        
                        while ($row = $result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($row['tgl_transaksi']) ?></td>
                            <td><?= htmlspecialchars($row['income']) ?></td>
                            <td>0</td> <!-- Placeholder for expenditure -->
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <h3>Work Report</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Staff</th>
                            <th>Total Transactions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch work data
                        $query = "SELECT p.nama_petugas, COUNT(t.id_transaksi) AS total_transactions 
                                  FROM tb_petugas p LEFT JOIN tb_transaksi t ON p.id_petugas = t.id_petugas 
                                  GROUP BY p.id_petugas";
                        $result = $conn->query($query);
                        
                        while ($row = $result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nama_petugas']) ?></td>
                            <td><?= htmlspecialchars($row['total_transactions']) ?></td>
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
