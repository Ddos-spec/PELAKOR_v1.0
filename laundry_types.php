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
    <title>Laundry Types Management - LaundryKu</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-4">
        <h1>Laundry Types Management</h1>
        
        <div class="mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLaundryTypeModal">
                Add New Laundry Type
            </button>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price per Kilo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM tb_jenis_cucian";
                $result = $conn->query($query);
                
                while ($row = $result->fetch(PDO::FETCH_ASSOC)):
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_jenis_cucian']) ?></td>
                    <td><?= htmlspecialchars($row['nama_jenis_cucian']) ?></td>
                    <td><?= htmlspecialchars($row['harga_satuan']) ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editLaundryTypeModal"
                            data-id="<?= $row['id_jenis_cucian'] ?>"
                            data-name="<?= htmlspecialchars($row['nama_jenis_cucian']) ?>"
                            data-price="<?= htmlspecialchars($row['harga_satuan']) ?>">
                            Edit
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteLaundryType(<?= $row['id_jenis_cucian'] ?>)">
                            Delete
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Laundry Type Modal -->
    <div class="modal fade" id="addLaundryTypeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Laundry Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addLaundryTypeForm">
                        <div class="mb-3">
                            <label for="laundryTypeName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="laundryTypeName" required>
                        </div>
                        <div class="mb-3">
                            <label for="laundryTypePrice" class="form-label">Price per Kilo</label>
                            <input type="number" class="form-control" id="laundryTypePrice" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="addLaundryType()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
