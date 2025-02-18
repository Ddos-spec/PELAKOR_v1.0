<!DOCTYPE html>
<?php 
include 'auth_check.php';
include 'header.php';
?>

<div class="container mt-4">
    <h1>Admin Dashboard</h1>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Officer Management</h5>
                    <a href="officers.php" class="btn btn-primary">Manage Officers</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Laundry Types</h5>
                    <a href="laundry_types.php" class="btn btn-primary">Manage Types</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Reports</h5>
                    <a href="reports.php" class="btn btn-primary">View Reports</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Inventory Management</h5>
                    <a href="inventory.php" class="btn btn-primary">Manage Inventory</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
