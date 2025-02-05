<?php
// report.php - Generate financial reports

include '../connect-db.php';

// Function to fetch financial data
function getFinancialReport($startDate, $endDate) {
    global $connect;
    $stmt = $connect->prepare("SELECT SUM(total_bayar) AS total_revenue, COUNT(*) AS total_transactions FROM transaksi WHERE tgl_mulai BETWEEN ? AND ?");
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Check for date range input
if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $report = getFinancialReport($startDate, $endDate);
} else {
    $report = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Report</title>
    <link rel="stylesheet" href="../uikit/dist/css/uikit.css">
</head>
<body>
    <div class="uk-container">
        <h1>Financial Report</h1>
        <form method="POST">
            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" required>
            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" required>
            <button type="submit" class="uk-button uk-button-primary">Generate Report</button>
        </form>

        <?php if ($report): ?>
            <h2>Report from <?php echo $startDate; ?> to <?php echo $endDate; ?></h2>
            <p>Total Revenue: <?php echo $report['total_revenue']; ?></p>
            <p>Total Transactions: <?php echo $report['total_transactions']; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
