<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../framework/style.css">
</head>
<body>
    <div class="container">
        <h2 class="center-align">Welcome to Admin Dashboard</h2>
        <p class="center-align">Here you can manage all the administrative tasks.</p>

        <h3>Statistics</h3>
        <?php
        include '../connect-db.php';

        // Total Admins
        $query = "SELECT COUNT(*) AS total_admins FROM admin";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        echo "<p>Total Admins: " . $row['total_admins'] . "</p>";

        // Total Agents
        $query = "SELECT COUNT(*) AS total_agents FROM agen";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        echo "<p>Total Agents: " . $row['total_agents'] . "</p>";

        // Total Customers
        $query = "SELECT COUNT(*) AS total_customers FROM pelanggan";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        echo "<p>Total Customers: " . $row['total_customers'] . "</p>";

        // Total Transactions
        $query = "SELECT COUNT(*) AS total_transactions FROM transaksi";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        echo "<p>Total Transactions: " . $row['total_transactions'] . "</p>";

        mysqli_close($conn);
        ?>
    </div>
    <script src="../framework/script.js"></script>
</body>
</html>
