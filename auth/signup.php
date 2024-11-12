<?php 
include('../includes/connection.php');

if(isset($_POST['signup'])){
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
    
    // Check if the username or email already exists
    $query = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $error = "Username or Email already exists!";
    } else {
        // Insert new user
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $username, $email, $password);
        if($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff; /* Light Blue */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .signup-container {
            background-color: #f5f5f5; /* Light Cream */
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
            border: 2px solid #89c9b8; /* Medium Blue */
        }
        .signup-container h2 {
            margin-bottom: 20px;
            color: #284b63; /* Dark Blue */
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #89c9b8; /* Medium Blue Border */
            border-radius: 8px;
            background-color: #f0f8ff; /* Light Blue Input Background */
            color: #284b63; /* Dark Blue Text */
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
            background-color: #1f3d50; /* Darker Blue Hover */
        }
        .error {
            color: #e74c3c; /* Red for error */
            margin-bottom: 15px;
        }
        .signup-container a {
            display: block;
            margin-top: 15px;
            color: #284b63; /* Dark Blue */
            text-decoration: none;
        }
        .signup-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Signup</h2>
        
        <!-- Display error message if any -->
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <!-- Signup Form -->
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="signup">Signup</button>
        </form>
        
        <a href="login.php">Already have an account? Log in</a>
    </div>
</body>
</html>
