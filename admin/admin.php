<!DOCTYPE html>
<html>
<head>
    <title>Admin Management</title>
    <link rel="stylesheet" href="../framework/style.css">
</head>
<body>
    <div class="container">
        <h2 class="center-align">Add New Admin</h2>
        <form method="post" action="">
            <div class="input-field">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-field">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Add Admin</button>
        </form>

        <h2 class="center-align">Edit Admin</h2>
        <form method="post" action="">
            <div class="input-field">
                <label for="edit_username">Username:</label>
                <input type="text" id="edit_username" name="edit_username" required>
            </div>
            <div class="input-field">
                <label for="edit_password">Password:</label>
                <input type="password" id="edit_password" name="edit_password" required>
            </div>
            <button type="submit" class="btn">Edit Admin</button>
        </form>

        <h2 class="center-align">Admin List</h2>
        <?php
        include '../connect-db.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["username"]) && isset($_POST["password"])) {
                $username = $_POST["username"];
                $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

                $query = "INSERT INTO admin (username, password) VALUES ('$username', '$password')";
                if (mysqli_query($conn, $query)) {
                    echo "New admin created successfully";
                } else {
                    echo "Error: " . $query . "<br>" . mysqli_error($conn);
                }
            } elseif (isset($_POST["edit_username"]) && isset($_POST["edit_password"])) {
                $edit_username = $_POST["edit_username"];
                $edit_password = password_hash($_POST["edit_password"], PASSWORD_DEFAULT);

                $query = "UPDATE admin SET username='$edit_username', password='$edit_password' WHERE id_admin=" . $_POST["id_admin"];
                if (mysqli_query($conn, $query)) {
                    echo "Admin updated successfully";
                } else {
                    echo "Error: " . $query . "<br>" . mysqli_error($conn);
                }
            } elseif (isset($_POST["delete_id"])) {
                $delete_id = $_POST["delete_id"];

                $query = "DELETE FROM admin WHERE id_admin='$delete_id'";
                if (mysqli_query($conn, $query)) {
                    echo "Admin deleted successfully";
                } else {
                    echo "Error: " . $query . "<br>" . mysqli_error($conn);
                }
            }
        }

        $query = "SELECT * FROM admin";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            echo "<table class='striped'>";
            echo "<thead><tr><th>ID Admin</th><th>Username</th><th>Password</th><th>Action</th></tr></thead><tbody>";
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row["id_admin"] . "</td>";
                echo "<td>" . $row["username"] . "</td>";
                echo "<td>" . $row["password"] . "</td>";
                echo "<td><a href='edit_admin.php?id=" . $row["id_admin"] . "' class='btn'>Edit</a> <a href='delete_admin.php?id=" . $row["id_admin"] . "' class='btn red'>Delete</a></td>";
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
    ?>
</body>
</html>
