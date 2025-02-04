<?php
session_start();
include 'connect-db.php';
include 'functions/functions.php'; // Include the functions file to access cekAdmin()

// Check if the user is an admin
cekAdmin();

// Handle password reset
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id']; // Get user ID from the form
    $newPassword = $_POST['new_password']; // Get new password from the form

    // Update the password in the database
    $query = "UPDATE pelanggan SET password = '" . md5($newPassword) . "' WHERE id_pelanggan = '$userId'";
    if (mysqli_query($connect, $query)) {
        // Check if the update was successful
        if (mysqli_affected_rows($connect) > 0) {
            echo "<script>alert('Password has been reset successfully.');</script>";
        } else {
            echo "<script>alert('Failed to reset password. No changes made.');</script>";
        }
    } else {
        echo "<script>alert('Error executing query: " . mysqli_error($connect) . "');</script>";
    }
    echo "<script>window.location = 'list-pelanggan.php';</script>"; // Redirect to list-pelanggan.php
}

// Display the password reset form
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="icon" href="img/laundryku.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css"> <!-- Link to your CSS file -->
    <link rel="icon" href="img/laundryku.ico" type="image/x-icon">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 50px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 350px;
        }
        h3 {
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        label {
            font-weight: bold;
            display: block;
        }
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            width: 105%;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background-color: #218838;
        }
        .banner {
            width: 110%;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="img/banner.png" class="banner" alt="Banner">
        <h3>Reset Password</h3>
        <form action="" method="post">
            <input type="hidden" name="user_id" value="<?php echo $_GET['user_id']; ?>">
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" required placeholder="Enter new password">
            </div>
            <button type="submit" class="btn">Reset Password</button>
        </form>
    </div>
</body>
</html>
