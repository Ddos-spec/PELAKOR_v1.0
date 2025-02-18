<?php include 'auth_check.php'; ?>
<?php include 'header.php'; ?>

<div class="container mt-4">
    <h1>Officer Dashboard</h1>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Customer Management</h5>
                    <a href="customers.php" class="btn btn-primary">Manage Customers</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Transactions</h5>
                    <a href="transactions.php" class="btn btn-primary">Manage Transactions</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Invoice Tracking</h5>
                    <a href="invoice_tracking.php" class="btn btn-primary">Track Invoices</a>
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