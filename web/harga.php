<!DOCTYPE html>
<html>
<head>
    <title>Harga Management</title>
    <link rel="stylesheet" href="../framework/style.css">
</head>
<body>
    <div class="container">
        <h2 class="center-align">Add New Harga</h2>
        <form method="post" action="">
            <div class="input-field">
                <label for="harga_name">Harga Name:</label>
                <input type="text" id="harga_name" name="harga_name" required>
            </div>
            <div class="input-field">
                <label for="harga_type">Type:</label>
                <input type="text" id="harga_type" name="harga_type" required>
            </div>
            <div class="input-field">
                <label for="harga_price">Price:</label>
                <input type="number" id="harga_price" name="harga_price" required>
            </div>
            <button type="submit" name="add_harga" class="btn">Add Harga</button>
        </form>

        <h2 class="center-align">Edit Harga</h2>
        <form method="post" action="">
            <div class="input-field">
                <label for="edit_id">Harga ID:</label>
                <input type="text" id="edit_id" name="edit_id" required>
            </div>
            <div class="input-field">
                <label for="edit_name">Harga Name:</label>
                <input type="text" id="edit_name" name="edit_name" required>
            </div>
            <div class="input-field">
                <label for="edit_type">Type:</label>
                <input type="text" id="edit_type" name="edit_type" required>
            </div>
            <div class="input-field">
                <label for="edit_price">Price:</label>
                <input type="number" id="edit_price" name="edit_price" required>
            </div>
            <button type="submit" name="edit_harga" class="btn">Edit Harga</button>
        </form>

        <h2 class="center-align">Delete Harga</h2>
        <form method="post" action="">
            <div class="input-field">
                <label for="delete_id">Harga ID:</label>
                <input type="text" id="delete_id" name="delete_id" required>
            </div>
            <button type="submit" name="delete_harga" class="btn red">Delete Harga</button>
        </form>

        <h2 class="center-align">Harga List</h2>
        <?php
        include '../connect-db.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["harga_name"]) && isset($_POST["harga_type"]) && isset($_POST["harga_price"])) {
                $harga_name = $_POST["harga_name"];
                $harga_type = $_POST["harga_type"];
                $harga_price = $_POST["harga_price"];

                $query = "INSERT INTO harga (name, type, price) VALUES ('$harga_name', '$harga_type', '$harga_price')";
                if (mysqli_query($conn, $query)) {
                    echo "New harga created successfully";
                } else {
                    echo "Error: " . $query . "<br>" . mysqli_error($conn);
                }
            } elseif (isset($_POST["edit_id"]) && isset($_POST["edit_name"]) && isset($_POST["edit_type"]) && isset($_POST["edit_price"])) {
                $edit_id = $_POST["edit_id"];
                $edit_name = $_POST["edit_name"];
                $edit_type = $_POST["edit_type"];
                $edit_price = $_POST["edit_price"];

                $query = "UPDATE harga SET name='$edit_name', type='$edit_type', price='$edit_price' WHERE id_harga='$edit_id'";
                if (mysqli_query($conn, $query)) {
                    echo "Harga updated successfully";
                } else {
                    echo "Error: " . $query . "<br>" . mysqli_error($conn);
                }
            } elseif (isset($_POST["delete_id"])) {
                $delete_id = $_POST["delete_id"];

                $query = "DELETE FROM harga WHERE id_harga='$delete_id'";
                if (mysqli_query($conn, $query)) {
                    echo "Harga deleted successfully";
                } else {
                    echo "Error: " . $query . "<br>" . mysqli_error($conn);
                }
            }
        }

        $query = "SELECT * FROM harga";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            echo "<table class='striped'>";
            echo "<thead><tr><th>ID Harga</th><th>Name</th><th>Type</th><th>Price</th><th>Action</th></tr></thead><tbody>";
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row["id_harga"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["type"] . "</td>";
                echo "<td>" . $row["price"] . "</td>";
                echo "<td><a href='edit_harga.php?id=" . $row["id_harga"] . "' class='btn'>Edit</a> <a href='delete_harga.php?id=" . $row["id_harga"] . "' class='btn red'>Delete</a></td>";
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
