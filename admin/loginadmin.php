<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body.admin {
            background-color: red;
        }
        body.agen {
            background-color: yellow;
        }
    </style>
</head>
<body class="admin">
    <h2>Login</h2>
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
    <button onclick="switchUser()">Switch User</button>

    <script>
        function switchUser() {
            var bodyClass = document.body.className;
            if (bodyClass === "admin") {
                document.body.className = "agen";
                document.getElementById("username").type = "email";
            } else {
                document.body.className = "admin";
                document.getElementById("username").type = "text";
            }
        }
    </script>

    <?php
    include '../connect-db.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $user_type = (document.body.className === "admin") ? "admin" : "agen";

        if ($user_type === "admin") {
            $query = "SELECT * FROM admin WHERE username='$username'";
        } else {
            $query = "SELECT * FROM agen WHERE email='$username'";
        }

        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $row["password"])) {
                echo "Login successful";
                if ($user_type === "admin") {
                    header("Location: dashboardadmin.php");
                } else {
                    header("Location: dashboardagen.php");
                }
                exit();
            } else {
                echo "Invalid password";
            }
        } else {
            echo "No user found";
        }
    }

    mysqli_close($conn);
    ?>
</body>
</html>
