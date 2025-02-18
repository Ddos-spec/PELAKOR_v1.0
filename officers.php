<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_officer'])) {
        // Add new officer
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO tb_petugas (nama_petugas, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password]);
    } elseif (isset($_POST['update_officer'])) {
        // Update officer
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        
        $stmt = $conn->prepare("UPDATE tb_petugas SET nama_petugas = ?, email = ? WHERE id_petugas = ?");
        $stmt->execute([$name, $email, $id]);
    } elseif (isset($_POST['delete_officer'])) {
        // Delete officer
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM tb_petugas WHERE id_petugas = ?");
        $stmt->execute([$id]);
    }
}

// Fetch all officers
$officers = $conn->query("SELECT * FROM tb_petugas")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Officer Management - LaundryKu</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-4">
        <h1>Officer Management</h1>
        
        <!-- Add Officer Form -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Add New Officer</h5>
                <form method="POST">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="name" class="form-control" placeholder="Name" required>
                        </div>
                        <div class="col-md-4">
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="col-md-3">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" name="add_officer" class="btn btn-primary">Add</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Officers Table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($officers as $officer): ?>
                <tr>
                    <td><?= htmlspecialchars($officer['id_petugas']) ?></td>
                    <td><?= htmlspecialchars($officer['nama_petugas']) ?></td>
                    <td><?= htmlspecialchars($officer['email']) ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editOfficerModal"
                            data-id="<?= $officer['id_petugas'] ?>"
                            data-name="<?= htmlspecialchars($officer['nama_petugas']) ?>"
                            data-email="<?= htmlspecialchars($officer['email']) ?>">
                            Edit
                        </button>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="id" value="<?= $officer['id_petugas'] ?>">
                            <button type="submit" name="delete_officer" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Edit Officer Modal -->
    <div class="modal fade" id="editOfficerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Officer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="editOfficerForm">
                        <input type="hidden" name="id" id="editOfficerId">
                        <div class="mb-3">
                            <label for="editOfficerName" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" id="editOfficerName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editOfficerEmail" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="editOfficerEmail" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="editOfficerForm" name="update_officer" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize edit modal with officer data
        const editOfficerModal = document.getElementById('editOfficerModal');
        editOfficerModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const email = button.getAttribute('data-email');
            
            const modal = this;
            modal.querySelector('#editOfficerId').value = id;
            modal.querySelector('#editOfficerName').value = name;
            modal.querySelector('#editOfficerEmail').value = email;
        });
    </script>
</body>
</html>
