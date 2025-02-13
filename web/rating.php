<!DOCTYPE html>
<html>
<head>
    <title>Rating and Review</title>
    <link rel="stylesheet" href="../framework/style.css">
</head>
<body>
    <div class="container">
        <h2 class="center-align">Rate and Review</h2>
        <form method="post" action="">
            <div class="input-field">
                <label for="transaksi_id">Transaksi ID:</label>
                <input type="text" id="transaksi_id" name="transaksi_id" required>
            </div>
            <div class="input-field">
                <label for="rating">Rating:</label>
                <input type="number" id="rating" name="rating" min="1" max="5" required>
            </div>
            <div class="input-field">
                <label for="review">Review:</label>
                <textarea id="review" name="review" required></textarea>
            </div>
            <button type="submit" class="btn">Submit</button>
        </form>

        <?php
        include '../connect-db.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $transaksi_id = $_POST["transaksi_id"];
            $rating = $_POST["rating"];
            $review = $_POST["review"];

            $query = "UPDATE transaksi SET rating='$rating', comment='$review' WHERE id_transaksi='$transaksi_id'";
            if (mysqli_query($conn, $query)) {
                echo "Rating and review submitted successfully";
            } else {
                echo "Error: " . $query . "<br>" . mysqli_error($conn);
            }
        }

        mysqli_close($conn);
        ?>
    </div>
    <script src="../framework/script.js"></script>
</body>
</html>
