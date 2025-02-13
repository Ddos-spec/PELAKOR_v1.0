<!DOCTYPE html>
<html>
<head>
    <title>Transaksi Management</title>
    <link rel="stylesheet" href="../framework/style.css">
</head>
<body>
    <div class="container">
        <h2 class="center-align">Add New Transaksi</h2>
        <form method="post" action="">
            <div class="input-field">
                <label for="transaksi_code">Transaksi Code:</label>
                <input type="text" id="transaksi_code" name="transaksi_code" required>
            </div>
            <div class="input-field">
                <label for="transaksi_id_cucian">ID Cucian:</label>
                <input type="text" id="transaksi_id_cucian" name="transaksi_id_cucian" required>
            </div>
            <div class="input-field">
                <label for="transaksi_id_agen">ID Agen:</label>
                <input type="text" id="transaksi_id_agen" name="transaksi_id_agen" required>
            </div>
            <div class="input-field">
                <label for="transaksi_id_pelanggan">ID Pelanggan:</label>
                <input type="text" id="transaksi_id_pelanggan" name="transaksi_id_pelanggan" required>
            </div>
            <div class="input-field">
                <label for="transaksi_start_date">Start Date:</label>
                <input type="date" id="transaksi_start_date" name="transaksi_start_date" required>
            </div>
            <div class="input-field">
                <label for="transaksi_end_date">End Date:</label>
                <input type="date" id="transaksi_end_date" name="transaksi_end_date" required>
            </div>
            <div class="input-field">
                <label for="transaksi_total">Total:</label>
                <input type="number" id="transaksi_total" name="transaksi_total" required>
            </div>
            <div class="input-field">
                <label for="transaksi_rating">Rating:</label>
                <input type="number" id="transaksi_rating" name="transaksi_rating" required>
            </div>
            <div class="input-field">
                <label for="transaksi_comment">Comment:</label>
                <input type="text" id="transaksi_comment" name="transaksi_comment" required>
            </div>
            <div class="input-field">
                <label for="transaksi_type">Type:</label>
                <input type="text" id="transaksi_type" name="transaksi_type" required>
            </div>
            <button type="submit" name="add_transaksi" class="btn">Add Transaksi</button>
        </form>

        <h2 class="center-align">Edit Transaksi</h2>
        <form method="post" action="">
            <div class="input-field">
                <label for="edit_id">Transaksi ID:</label>
                <input type="text" id="edit_id" name="edit_id" required>
            </div>
            <div class="input-field">
                <label for="edit_code">Transaksi Code:</label>
                <input type="text" id="edit_code" name="edit_code" required>
            </div>
            <div class="input-field">
                <label for="edit_id_cucian">ID Cucian:</label>
                <input type="text" id="edit_id_cucian" name="edit_id_cucian" required>
            </div>
            <div class="input-field">
                <label for="edit_id_agen">ID Agen:</label>
                <input type="text" id="edit_id_agen" name="edit_id_agen" required>
            </div>
            <div class="input-field">
                <label for="edit_id_pelanggan">ID Pelanggan:</label>
                <input type="text" id="edit_id_pelanggan" name="edit_id_pelanggan" required>
            </div>
            <div class="input-field">
                <label for="edit_start_date">Start Date:</label>
                <input type="date" id="edit_start_date" name="edit_start_date" required>
            </div>
            <div class="input-field">
                <label for="edit_end_date">End Date:</label>
                <input type="date" id="edit_end_date" name="edit_end_date" required>
            </div>
            <div class="input-field">
                <label for="edit_total">Total:</label>
                <input type="number" id="edit_total" name="edit_total" required>
            </div>
            <div class="input-field">
                <label for="edit_rating">Rating:</label>
                <input type="number" id="edit_rating" name="edit_rating" required>
            </div>
            <div class="input-field">
                <label for="edit_comment">Comment:</label>
                <input type="text" id="edit_comment" name="edit_comment" required>
            </div>
            <div class="input-field">
                <label for="edit_type">Type:</label>
                <input type="text" id="edit_type" name="edit_type" required>
            </div>
            <button type="submit" name="edit_transaksi" class="btn">Edit Transaksi</button>
        </form>

        <h2 class="center-align">Delete Transaksi</h2>
        <form method="post" action="">
            <div class="input-field">
                <label for="delete_id">Transaksi ID:</label>
                <input type="text" id="delete_id" name="delete_id" required>
            </div>
            <button type="submit" name="delete_transaksi" class="btn red">Delete Transaksi</button>
        </form>

        <h2 class="center-align">Transaksi List</h2>
        <?php
        include '../connect-db.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["transaksi_code"]) && isset($_POST["transaksi_id_cucian"]) && isset($_POST["transaksi_id_agen"]) && isset($_POST["transaksi_id_pelanggan"]) && isset($_POST["transaksi_start_date"]) && isset($_POST["transaksi_end_date"]) && isset($_POST["transaksi_total"]) && isset($_POST["transaksi_rating"]) && isset($_POST["transaksi_comment"]) && isset($_POST["transaksi_type"])) {
                $transaksi_code = $_POST["transaksi_code"];
                $transaksi_id_cucian = $_POST["transaksi_id_cucian"];
                $transaksi_id_agen = $_POST["transaksi_id_agen"];
                $transaksi_id_pelanggan = $_POST["transaksi_id_pelanggan"];
                $transaksi_start_date = $_POST["transaksi_start_date"];
                $transaksi_end_date = $_POST["transaksi_end_date"];
                $transaksi_total = $_POST["transaksi_total"];
                $transaksi_rating = $_POST["transaksi_rating"];
                $transaksi_comment = $_POST["transaksi_comment"];
                $transaksi_type = $_POST["transaksi_type"];

                $query = "INSERT INTO transaksi (code, id_cucian, id_agen, id_pelanggan, start_date, end_date, total, rating, comment, type) VALUES ('$transaksi_code', '$transaksi_id_cucian', '$transaksi_id_agen', '$transaksi_id_pelanggan', '$transaksi_start_date', '$transaksi_end_date', '$transaksi_total', '$transaksi_rating', '$transaksi_comment', '$transaksi_type')";
                if (mysqli_query($conn, $query)) {
                    echo "New transaksi created successfully";
                } else {
                    echo "Error: " . $query . "<br>" . mysqli_error($conn);
                }
            } elseif (isset($_POST["edit_id"]) && isset($_POST["edit_code"]) && isset($_POST["edit_id_cucian"]) && isset($_POST["edit_id_agen"]) && isset($_POST["edit_id_pelanggan"]) && isset($_POST["edit_start_date"]) && isset($_POST["edit_end_date"]) && isset($_POST["edit_total"]) && isset($_POST["edit_rating"]) && isset($_POST["edit_comment"]) && isset($_POST["edit_type"])) {
                $edit_id = $_POST["edit_id"];
                $edit_code = $_POST["edit_code"];
                $edit_id_cucian = $_POST["edit_id_cucian"];
                $edit_id_agen = $_POST["edit_id_agen"];
                $edit_id_pelanggan = $_POST["edit_id_pelanggan"];
                $edit_start_date = $_POST["edit_start_date"];
                $edit_end_date = $_POST["edit_end_date"];
                $edit_total = $_POST["edit_total"];
                $edit_rating = $_POST["edit_rating"];
                $edit_comment = $_POST["edit_comment"];
                $edit_type = $_POST["edit_type"];

                $query = "UPDATE transaksi SET code='$edit_code', id_cucian='$edit_id_cucian', id_agen='$edit_id_agen', id_pelanggan='$edit_id_pelanggan', start_date='$edit_start_date', end_date='$edit_end_date', total='$edit_total', rating='$edit_rating', comment='$edit_comment', type='$edit_type' WHERE id_transaksi='$edit_id'";
                if (mysqli_query($conn, $query)) {
                    echo "Transaksi updated successfully";
                } else {
                    echo "Error: " . $query . "<br>" . mysqli_error($conn);
                }
            } elseif (isset($_POST["delete_id"])) {
                $delete_id = $_POST["delete_id"];

                $query = "DELETE FROM transaksi WHERE id_transaksi='$delete_id'";
                if (mysqli_query($conn, $query)) {
                    echo "Transaksi deleted successfully";
                } else {
                    echo "Error: " . $query . "<br>" . mysqli_error($conn);
                }
            }
        }

        $query = "SELECT * FROM transaksi";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            echo "<table class='striped'>";
            echo "<thead><tr><th>ID Transaksi</th><th>Code</th><th>ID Cucian</th><th>ID Agen</th><th>ID Pelanggan</th><th>Start Date</th><th>End Date</th><th>Total</th><th>Rating</th><th>Comment</th><th>Type</th><th>Action</th></tr></thead><tbody>";
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row["id_transaksi"] . "</td>";
                echo "<td>" . $row["code"] . "</td>";
                echo "<td>" . $row["id_cucian"] . "</td>";
                echo "<td>" . $row["id_agen"] . "</td>";
                echo "<td>" . $row["id_pelanggan"] . "</td>";
                echo "<td>" . $row["start_date"] . "</td>";
                echo "<td>" . $row["end_date"] . "</td>";
                echo "<td>" . $row["total"] . "</td>";
                echo "<td>" . $row["rating"] . "</td>";
                echo "<td>" . $row["comment"] . "</td>";
                echo "<td>" . $row["type"] . "</td>";
                echo "<td><a href='edit_transaksi.php?id=" . $row["id_transaksi"] . "' class='btn'>Edit</a> <a href='delete_transaksi.php?id=" . $row["id_transaksi"] . "' class='btn red'>Delete</a></td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "0 results";
        }

        mysqli_close($conn);
        ?>
    </div>
    <script src="../framework/script.js"></script>
</body>
</html>
