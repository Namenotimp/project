<?php
// Include the database connection
include('../includes/connection.php');
session_start();

// Initialize variables
$error = '';

// Check if the login form has been submitted
if (isset($_POST['login'])) {
    // Sanitize user input to prevent SQL injection
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($username) || empty($password)) {
        $error = "Please fill in both the username and password.";
    } else {
        // Prepare and execute the query to fetch user data
        $query = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        // Bind parameters and execute
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if user exists
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Create session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // Redirect to the homepage
                header("Location: ../index.php");
                exit();
            } else {
                // If password verification fails
                $error = "Invalid username or password.";
            }
        } else {
            // If no user found with the provided credentials
            $error = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff; /* Light Blue Background */
            color: #284b63; /* Dark Blue Text */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #f5f5f5; /* Light Cream Background */
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
            border: 2px solid #89c9b8; /* Medium Blue Border */
        }
        .login-container h2 {
            margin-bottom: 20px;
            color: #284b63; /* Dark Blue */
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #89c9b8; /* Medium Blue Border */
            border-radius: 8px;
            background-color: #f0f8ff; /* Light Blue Input Background */
            color: #284b63; /* Dark Blue Input Text */
        }
        button {
            background-color: #284b63; /* Dark Blue Button */
            color: #f0f8ff; /* Light Blue Text */
            border: none;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
            margin-top: 10px;
        }
        button:hover {
            background-color: #1f3d50; /* Darker Blue Hover State */
        }
        .error {
            color: #e74c3c; /* Red for error messages */
            margin-bottom: 15px;
        }
        .login-container a {
            display: block;
            margin-top: 15px;
            color: #284b63; /* Dark Blue */
            text-decoration: none;
        }
        .login-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <!-- Display error message if any -->
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username or Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <a href="../auth/signup.php">Don't have an account? Sign up</a> <!-- Add a signup link -->
    </div>
</body>
</html>
