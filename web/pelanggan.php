<!DOCTYPE html>
<html>
<head>
    <title>Pelanggan Management</title>
    <link rel="stylesheet" href="../framework/style.css">
</head>
<body>
    <div class="container">
        <h2 class="center-align">Add New Pelanggan</h2>
        <form method="post" action="">
            <div class="input-field">
                <label for="pelanggan_name">Pelanggan Name:</label>
                <input type="text" id="pelanggan_name" name="pelanggan_name" required>
            </div>
            <div class="input-field">
                <label for="pelanggan_email">Email:</label>
                <input type="email" id="pelanggan_email" name="pelanggan_email" required>
            </div>
            <div class="input-field">
                <label for="pelanggan_password">Password:</label>
                <input type="password" id="pelanggan_password" name="pelanggan_password" required>
            </div>
            <button type="submit" name="add_pelanggan" class="btn">Add Pelanggan</button>
        </form>

        <h2 class="center-align">Edit Pelanggan</h2>
        <form method="post" action="">
            <div class="input-field">
                <label for="edit_id">Pelanggan ID:</label>
                <input type="text" id="edit_id" name="edit_id" required>
            </div>
            <div class="input-field">
                <label for="edit_name">Pelanggan Name:</label>
                <input type="text" id="edit_name" name="edit_name" required>
            </div>
            <div class="input-field">
                <label for="edit_email">Email:</label>
                <input type="email" id="edit_email" name="edit_email" required>
            </div>
            <div class="input-field">
                <label for="edit_password">Password:</label>
                <input type="password" id="edit_password" name="edit_password" required>
            </div>
            <button type="submit" name="edit_pelanggan" class="btn">Edit Pelanggan</button>
        </form>

        <h2 class="center-align">Delete Pelanggan</h2>
        <form method="post" action="">
            <div class="input-field">
                <label for="delete_id">Pelanggan ID:</label>
                <input type="text" id="delete_id" name="delete_id" required>
            </div>
            <button type="submit" name="delete_pelanggan" class="btn red">Delete Pelanggan</button>
        </form>

        <h2 class="center-align">Pelanggan List</h2>
        <?php
        include '../connect-db.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["pelanggan_name"]) && isset($_POST["pelanggan_email"]) && isset($_POST["pelanggan_password"])) {
                $pelanggan_name = $_POST["pelanggan_name"];
                $pelanggan_email = $_POST["pelanggan_email"];
                $pelanggan_password = password_hash($_POST["pelanggan_password"], PASSWORD_DEFAULT);

                $query = "INSERT INTO pelanggan (name, email, password) VALUES ('$pelanggan_name', '$pelanggan_email', '$pelanggan_password')";
                if (mysqli_query($conn, $query)) {
                    echo "New pelanggan created successfully";
                } else {
                    echo "Error: " . $query . "<br>" . mysqli_error($conn);
                }
            } elseif (isset($_POST["edit_id"]) && isset($_POST["edit_name"]) && isset($_POST["edit_email"]) && isset($_POST["edit_password"])) {
                $edit_id = $_POST["edit_id"];
                $edit_name = $_POST["edit_name"];
                $edit_email = $_POST["edit_email"];
                $edit_password = password_hash($_POST["edit_password"], PASSWORD_DEFAULT);

                $query = "UPDATE pelanggan SET name='$edit_name', email='$edit_email', password='$edit_password' WHERE id_pelanggan='$edit_id'";
                if (mysqli_query($conn, $query)) {
                    echo "Pelanggan updated successfully";
                } else {
                    echo "Error: " . $query . "<br>" . mysqli_error($conn);
                }
            } elseif (isset($_POST["delete_id"])) {
                $delete_id = $_POST["delete_id"];

                $query = "DELETE FROM pelanggan WHERE id_pelanggan='$delete_id'";
                if (mysqli_query($conn, $query)) {
                    echo "Pelanggan deleted successfully";
                } else {
                    echo "Error: " . $query . "<br>" . mysqli_error($conn);
                }
            }
        }

        $query = "SELECT * FROM pelanggan";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            echo "<table class='striped'>";
            echo "<thead><tr><th>ID Pelanggan</th><th>Name</th><th>Email</th><th>Password</th><th>Action</th></tr></thead><tbody>";
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row["id_pelanggan"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td>" . $row["password"] . "</td>";
                echo "<td><a href='edit_pelanggan.php?id=" . $row["id_pelanggan"] . "' class='btn'>Edit</a> <a href='delete_pelanggan.php?id=" . $row["id_pelanggan"] . "' class='btn red'>Delete</a></td>";
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
