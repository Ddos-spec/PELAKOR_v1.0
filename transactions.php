<?php 
require_once 'db_connection.php';
include 'auth_check.php'; 
include 'header.php'; 
?>

<div class="container mt-4">
    <h1>Transaction Management</h1>
    
    <div class="mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
            Add New Transaction
        </button>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Invoice #</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Weight (kg)</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Est. Pickup</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT t.id_transaksi, c.nama_customer, t.tgl_transaksi, t.status,
                             t.payment_status, t.invoice_number, jt.weight, jt.estimated_pickup_date
                      FROM tb_transaksi t
                      JOIN tb_customer c ON t.id_customer = c.id_customer
                      LEFT JOIN job_tracking jt ON t.id_transaksi = jt.id_transaksi";
            $result = $conn->query($query);
            
            while ($row = $result->fetch(PDO::FETCH_ASSOC)):
            ?>
            <tr>
                <td><?= htmlspecialchars($row['invoice_number']) ?></td>
                <td><?= htmlspecialchars($row['nama_customer']) ?></td>
                <td><?= htmlspecialchars($row['tgl_transaksi']) ?></td>
                <td><?= htmlspecialchars($row['weight']) ?></td>
                <td><?= getStatusText($row['status']) ?></td>
                <td><?= htmlspecialchars(ucfirst($row['payment_status'])) ?></td>
                <td><?= htmlspecialchars($row['estimated_pickup_date']) ?></td>
                <td>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editTransactionModal"
                        data-id="<?= $row['id_transaksi'] ?>">
                        Edit
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteTransaction(<?= $row['id_transaksi'] ?>)">
                        Delete
                    </button>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Add Transaction Modal -->
<div class="modal fade" id="addTransactionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addTransactionForm">
                    <div class="mb-3">
                        <label for="customerSelect" class="form-label">Customer</label>
                        <select class="form-select" id="customerSelect" required>
                            <?php
                            $customers = $conn->query("SELECT id_customer, nama_customer FROM tb_customer");
                            while ($customer = $customers->fetch(PDO::FETCH_ASSOC)):
                            ?>
                            <option value="<?= $customer['id_customer'] ?>">
                                <?= htmlspecialchars($customer['nama_customer']) ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="transactionDate" class="form-label">Date</label>
                        <input type="date" class="form-control" id="transactionDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="transactionWeight" class="form-label">Weight (kg)</label>
                        <input type="number" step="0.1" class="form-control" id="transactionWeight" required>
                    </div>
                    <div class="mb-3">
                        <label for="estimatedPickup" class="form-label">Estimated Pickup Date</label>
                        <input type="date" class="form-control" id="estimatedPickup" required>
                    </div>
                    <div class="mb-3">
                        <label for="transactionStatus" class="form-label">Status</label>
                        <select class="form-select" id="transactionStatus" required>
                            <option value="0">Received</option>
                            <option value="1">Processing</option>
                            <option value="2">Completed</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="addTransaction()">Save</button>
            </div>
        </div>
    </div>
</div>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
</body>
</html>
<?php
function getStatusText($status) {
    switch ($status) {
        case 0: return 'Received';
        case 1: return 'Processing';
        case 2: return 'Completed';
        default: return 'Unknown';
    }
}
?>