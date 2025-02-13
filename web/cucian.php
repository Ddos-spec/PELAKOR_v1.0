<!DOCTYPE html>
<html>
<head>
    <title>Cucian Management</title>
    <link rel="stylesheet" href="../framework/style.css">
</head>
<body>
    <div class="container">
        <h2 class="center-align">Add New Cucian</h2>
        <form method="post" action="">
            <div class="input-field">
                <label for="cucian_name">Cucian Name:</label>
                <input type="text" id="cucian_name" name="cucian_name" required>
            </div>
            <div class="input-field">
                <label for="cucian_type">Type:</label>
                <input type="text" id="cucian_type" name="cucian_type" required>
            </div>
            <div class="input-field">
                <label for="cucian_weight">Weight:</label>
                <input type="number" id="cucian_weight" name="cucian_weight" required>
            </div>
            <div class="input-field">
                <label for="cucian_status">Status:</label>
                <input type="text" id="cucian_status" name="cucian_status" required>
            </div>
            <button type="submit" name="add_cucian" class="btn">Add Cucian</button>
        </form>

        <h2 class="center-align">Edit Cucian</h2>
        <form method="post" action="">
            <div class="input-field">
                <label for="edit_id">Cucian ID:</label>
                <input type="text" id="edit_id" name="edit_id" required>
            </div>
            <div class="input-field">
                <label for="edit_name">Cucian Name:</label>
                <input type="text" id="edit_name" name="edit_name" required>
            </div>
            <div class="input-field">
                <label for="edit_type">Type:</label>
                <input type="text" id="edit_type" name="edit_type" required>
            </div>
            <div class="input-field">
                <label for="edit_weight">Weight:</label>
                <input type="number" id="edit_weight" name="edit_weight" required>
            </div>
            <div class="input-field">
                <label for="edit_status">Status:</label>
                <input type="text" id="edit_status" name="edit_status" required>
            </div>
            <button type="submit" name="edit_cucian" class="btn">Edit Cucian</button>
        </form>

        <h2 class="center-align">Delete Cucian</h2>
        <form method="post" action="">
            <div class="input-field">
                <label for="delete_id">Cucian ID:</label>
                <input type="text" id="delete_id" name="delete_id" required>
            </div>
            <button type="submit" name="delete_cucian" class="btn red">Delete Cucian</button>
        </form>

        <h2 class="center-align">Cucian List</h2>
        <?php
        include '../connect-db.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["cucian_name"]) && isset($_POST["cucian_type"]) && isset($_POST["cucian_weight"]) && isset($_POST["cucian_status"])) {
                $cucian_name = $_POST["cucian_name"];
                $cucian_type = $_POST["cucian_type"];
                $cucian_weight = $_POST["cucian_weight"];
                $cucian_status = $_POST["cucian_status"];

                $query = "INSERT INTO cucian (name, type, weight, status) VALUES ('$cucian_name', '$cucian_type', '$cucian_weight', '$cucian_status')";
                if (mysqli_query($conn, $query)) {
                    echo "New cucian created successfully";
                } else {
                    echo "Error: " . $query . "<br>" . mysqli_error($conn);
                }
                // Send notification to customer
                $query = "SELECT email FROM pelanggan WHERE id_pelanggan=(SELECT id_pelanggan FROM transaksi WHERE id_cucian='$edit_id')";
                $result = mysqli_query($conn, $query);
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $to = $row['email'];
                    $subject = "Laundry Status Update";
                    $message = "Dear Customer,\n\nYour laundry status has been updated to: $edit_status.\n\nThank you for using our service.";
                    $headers = "From: no-reply@laundryservice.com";

                    mail($to, $subject, $message, $headers);
                }
            } elseif (isset($_POST["delete_id"])) {
                $delete_id = $_POST["delete_id"];

                $query = "DELETE FROM cucian WHERE id_cucian='$delete_id'";
                if (mysqli_query($conn, $query)) {
                    echo "Cucian deleted successfully";
                } else {
                    echo "Error: " . $query . "<br>" . mysqli_error($conn);
                }
            }
        }

        $query = "SELECT * FROM cucian";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            echo "<table class='striped'>";
            echo "<thead><tr><th>ID Cucian</th><th>Name</th><th>Type</th><th>Weight</th><th>Status</th><th>Action</th></tr></thead><tbody>";
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row["id_cucian"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["type"] . "</td>";
                echo "<td>" . $row["weight"] . "</td>";
                echo "<td>" . $row["status"] . "</td>";
                echo "<td><a href='edit_cucian.php?id=" . $row["id_cucian"] . "' class='btn'>Edit</a> <a href='delete_cucian.php?id=" . $row["id_cucian"] . "' class='btn red'>Delete</a></td>";
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
