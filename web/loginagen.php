<!DOCTYPE html>
<html>
<head>
    <title>Login Agen</title>
    <link rel="stylesheet" href="../framework/style.css">
</head>
<body>
    <div class="container">
        <h2 class="center-align">Login Agen</h2>
        <form method="post" action="">
            <div class="input-field">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-field">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>

        <?php
        include '../connect-db.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST["email"];
            $password = $_POST["password"];

            $query = "SELECT * FROM agen WHERE email='$email'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                if (password_verify($password, $row["password"])) {
                    echo "Login successful";
                    header("Location: ../admin/dashboardagen.php");
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
    </div>
    <script src="../framework/script.js"></script>
</body>
</html>
