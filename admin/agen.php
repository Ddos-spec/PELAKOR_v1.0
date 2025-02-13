<!DOCTYPE html>
<html>
<head>
    <title>Agen Management</title>
    <link rel="stylesheet" href="../framework/style.css">
</head>
<body>
    <div class="container">
        <h2 class="center-align">Add New Agen</h2>
        <form method="post" action="">
            <div class="input-field">
                <label for="agen_name">Agen Name:</label>
                <input type="text" id="agen_name" name="agen_name" required>
            </div>
            <div class="input-field">
                <label for="agen_email">Email:</label>
                <input type="email" id="agen_email" name="agen_email" required>
            </div>
            <div class="input-field">
                <label for="agen_password">Password:</label>
                <input type="password" id="agen_password" name="agen_password" required>
            </div>
            <button type="submit" name="add_agen" class="btn">Add Agen</button>
        </form>

        <h2 class="center-align">Edit Agen</h2>
        <form method="post" action="">
            <div class="input-field">
                <label for="edit_id">Agen ID:</label>
                <input type="text" id="edit_id" name="edit_id" required>
            </div>
            <div class="input-field">
                <label for="edit_name">Agen Name:</label>
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
            <button type="submit" name="edit_agen" class="btn">Edit Agen</button>
        </form>

        <h2 class="center-align">Delete Agen</h2>
        <form method="post" action="">
            <div class="input-field">
                <label for="delete_id">Agen ID:</label>
                <input type="text" id="delete_id" name="delete_id" required>
            </div>
            <button type="submit" name="delete_agen" class="btn red">Delete Agen</button>
        </form>

        <h2 class="center-align">Agen List</h2>
        <?php
        include '../connect-db.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["agen_name"]) && isset($_POST["agen_email"]) && isset($_POST["agen_password"])) {
                $agen_name = $_POST["agen_name"];
                $agen_email = $_POST["agen_email"];
                $agen_password = password_hash($_POST["agen_password"], PASSWORD_DEFAULT);

                $query = "INSERT INTO agen (name, email, password) VALUES ('$agen_name', '$agen_email', '$agen_password')";
                if (mysqli_query($conn, $query)) {
                    echo "New agen created successfully";
                } else {
                    echo "Error: " . $query . "<br>" . mysqli_error($conn);
                }
            } elseif (isset($_POST["edit_id"]) && isset($_POST["edit_name"]) && isset($_POST["edit_email"]) && isset($_POST["edit_password"])) {
                $edit_id = $_POST["edit_id"];
                $edit_name = $_POST["edit_name"];
                $edit_email = $_POST["edit_email"];
                $edit_password = password_hash($_POST["edit_password"], PASSWORD_DEFAULT);

                $query = "UPDATE agen SET name='$edit_name', email='$edit_email', password='$edit_password' WHERE id_agen='$edit_id'";
                if (mysqli_query($conn, $query)) {
                    echo "Agen updated successfully";
                } else {
                    echo "Error: " . $query . "<br>" . mysqli_error($conn);
                }
            } elseif (isset($_POST["delete_id"])) {
                $delete_id = $_POST["delete_id"];

                $query = "DELETE FROM agen WHERE id_agen='$delete_id'";
                if (mysqli_query($conn, $query)) {
                    echo "Agen deleted successfully";
                } else {
                    echo "Error: " . $query . "<br>" . mysqli_error($conn);
                }
            }
        }

        $query = "SELECT * FROM agen";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            echo "<table class='striped'>";
            echo "<thead><tr><th>ID Agen</th><th>Name</th><th>Email</th><th>Password</th><th>Action</th></tr></thead><tbody>";
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row["id_agen"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td>" . $row["password"] . "</td>";
                echo "<td><a href='edit_agen.php?id=" . $row["id_agen"] . "' class='btn'>Edit</a> <a href='delete_agen.php?id=" . $row["id_agen"] . "' class='btn red'>Delete</a></td>";
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
