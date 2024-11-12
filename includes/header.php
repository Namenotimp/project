<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bike Recommendation</title>
    <style>
        /* Header Styles */
        header {
            background-color: #f1f1f1; /* Light Gray Background */
            padding: 15px 30px;
            border-bottom: 1px solid #ddd; /* Light Gray Border */
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-links {
            display: flex;
            gap: 20px;
        }

        .nav-links a {
            color: #fff; /* White Text */
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #007bff; /* Primary Color */
            transition: background-color 0.3s;
        }

        .nav-links a:hover {
            background-color: #ff4d4d; /* Red on Hover */
        }

        .user-options {
            display: flex;
            gap: 10px;
        }

        .logout-link {
            color: #007bff; /* Primary Color */
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #fff; /* White Background */
            transition: background-color 0.3s;
        }

        .logout-link:hover {
            background-color: #ff4d4d; /* Red on Hover */
            color: #fff; /* White Text */
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="bikes/index.php">Bikes List</a>
            </div>

            <div class="user-options">
                <?php
                session_start();
                $isLoggedIn = isset($_SESSION['user_id']);
                $username = $isLoggedIn ? $_SESSION['username'] : '';
                $firstLetter = $isLoggedIn ? strtoupper($username[0]) : '';
                
                if ($isLoggedIn): ?>
                    <a href="auth/logout.php" class="logout-link">Logout</a>
                <?php else: ?>
                    <a href="auth/login.php" class="nav-links">Login</a>
                    <a href="auth/signup.php" class="nav-links">Signup</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
</body>
</html>
