<?php
session_start();
include('../includes/connection.php');

// Check if the user is already logged in
if (isset($_SESSION['admin_id'])) {
    header('Location: admin_panel.php'); // Redirect to admin panel if already logged in
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
    // Get and sanitize input
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Check if the username already exists
    $query = "SELECT * FROM admins WHERE username=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $error = "Username already taken.";
    } else {
        // Insert new admin into the database
        $query = "INSERT INTO admins (username, password) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'ss', $username, $hashed_password);
        if (mysqli_stmt_execute($stmt)) {
            header('Location: admin_login.php'); // Redirect to login page
            exit();
        } else {
            $error = "Error creating account. Please try again.";
        }
        // Close the statement
        mysqli_stmt_close($stmt);
    }

    // Close the database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Signup</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fef3e2;
            color: #161606;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h1 {
            color: #708870;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
            text-align: left;
        }
        input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        button {
            background-color: #708870;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #161606;
        }
        .error {
            color: #dc3545;
            margin: 10px 0;
        }
        .link {
            color: #708870;
            text-decoration: none;
        }
        .link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Signup</h1>
        <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form action="admin_signup.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" name="signup">Signup</button>
        </form>
        <p>Already have an account? <a href="admin_login.php" class="link">Login</a></p>
    </div>
</body>
</html>
