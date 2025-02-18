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
    <title>Inventory Management - LaundryKu</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-4">
        <h1>Inventory Management</h1>
        
        <div class="mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addInventoryModal">
                Add New Inventory Item
            </button>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM inventory";
                $result = $conn->query($query);
                
                while ($row = $result->fetch(PDO::FETCH_ASSOC)):
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['item_name']) ?></td>
                    <td><?= htmlspecialchars($row['quantity']) ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editInventoryModal"
                            data-id="<?= $row['id'] ?>"
                            data-name="<?= htmlspecialchars($row['item_name']) ?>"
                            data-stock="<?= htmlspecialchars($row['quantity']) ?>">
                            Edit
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteInventory(<?= $row['id'] ?>)">
                            Delete
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Inventory Modal -->
    <div class="modal fade" id="addInventoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Inventory Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addInventoryForm">
                        <div class="mb-3">
                            <label for="inventoryName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="inventoryName" required>
                        </div>
                        <div class="mb-3">
                            <label for="inventoryStock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="inventoryStock" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="addInventory()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Inventory Modal -->
    <div class="modal fade" id="editInventoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Inventory Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editInventoryForm">
                        <input type="hidden" id="editInventoryId">
                        <div class="mb-3">
                            <label for="editInventoryName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="editInventoryName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editInventoryStock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="editInventoryStock" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateInventory()">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
