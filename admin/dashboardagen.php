<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Agen</title>
    <link rel="stylesheet" href="../framework/style.css">
</head>
<body>
    <div class="container">
        <h2 class="center-align">Welcome to Agen Dashboard</h2>
        <p class="center-align">Here you can manage all the tasks related to your laundry service.</p>

        <h3>Statistics</h3>
        <?php
        include '../connect-db.php';

        // Total Customers
        $query = "SELECT COUNT(*) AS total_customers FROM pelanggan";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        echo "<p>Total Customers: " . $row['total_customers'] . "</p>";

        // Total Transactions
        $query = "SELECT COUNT(*) AS total_transactions FROM transaksi WHERE id_agen=" . $_SESSION['id_agen'];
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        echo "<p>Total Transactions: " . $row['total_transactions'] . "</p>";

        mysqli_close($conn);
        ?>
    </div>
    <script src="../framework/script.js"></script>
</body>
</html>
