<?php
include('../includes/connection.php'); // Adjust the path as necessary
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php'); // Redirect to admin login page if not signed in
    exit();
}

// Check if connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Define allowed image types
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

// Fetch categories for the dropdown
$categories = [];
$categoryQuery = "SELECT DISTINCT category FROM bikes";
$categoryResult = mysqli_query($conn, $categoryQuery);
if ($categoryResult) {
    while ($row = mysqli_fetch_assoc($categoryResult)) {
        $categories[] = $row['category'];
    }
}

// Initialize variables
$brand_id = '';
$name = '';
$category = '';
$price = '';
$mileage = '';
$engine_capacity = '';
$image = '';

// Initialize error array
$errors = [];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Common validation
    $brand_id = !empty($_POST['brand_id']) ? mysqli_real_escape_string($conn, $_POST['brand_id']) : '';
    $name = !empty($_POST['name']) ? mysqli_real_escape_string($conn, $_POST['name']) : '';
    $category = !empty($_POST['category']) ? mysqli_real_escape_string($conn, $_POST['category']) : '';
    $price = !empty($_POST['price']) ? mysqli_real_escape_string($conn, $_POST['price']) : '';
    $mileage = !empty($_POST['mileage']) ? mysqli_real_escape_string($conn, $_POST['mileage']) : '';
    $engine_capacity = !empty($_POST['engine_capacity']) ? mysqli_real_escape_string($conn, $_POST['engine_capacity']) : '';

    if (empty($brand_id) || empty($name) || empty($category) || empty($price) || empty($mileage) || empty($engine_capacity)) {
        $errors[] = "All fields are required.";
    }

    if (!is_numeric($price) || $price <= 0) {
        $errors[] = "Price must be a positive numeric value.";
    }

    if (!is_numeric($mileage) || $mileage < 0) {
        $errors[] = "Mileage must be a non-negative numeric value.";
    }

    if (!is_numeric($engine_capacity) || $engine_capacity <= 0) {
        $errors[] = "Engine capacity must be a positive numeric value.";
    }

    // Handle form submissions
    if (isset($_POST['add_bike'])) {
        // Handle Image Upload
        if (empty($_FILES['image']['name'])) {
            $errors[] = "Image is required.";
        }

        if (empty($errors)) {
            $image = $_FILES['image']['name'];
            $target = "../assets/images/" . basename($image);

            if (in_array($_FILES['image']['type'], $allowedTypes) && move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $query = "INSERT INTO bikes (brand_id, name, category, price, mileage, engine_capacity, image) 
                          VALUES ('$brand_id', '$name', '$category', '$price', '$mileage', '$engine_capacity', '$image')";
            } else {
                $errors[] = "Error uploading image. Make sure it is a valid image file.";
            }

            if (empty($errors) && mysqli_query($conn, $query)) {
                echo "<p class='success'>Bike added successfully!</p>";
            } else {
                echo "<p class='error'>Error: " . mysqli_error($conn) . "</p>";
            }
        } else {
            foreach ($errors as $error) {
                echo "<p class='error'>$error</p>";
            }
        }
    } elseif (isset($_POST['edit_bike'])) {
        $id = !empty($_POST['id']) ? mysqli_real_escape_string($conn, $_POST['id']) : '';

        if (empty($errors)) {
            // Check if a new image is uploaded
            if (!empty($_FILES['image']['name'])) {
                $image = $_FILES['image']['name'];
                $target = "../assets/images/" . basename($image);

                if (in_array($_FILES['image']['type'], $allowedTypes) && move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                    $query = "UPDATE bikes SET brand_id='$brand_id', name='$name', category='$category', price='$price', mileage='$mileage', engine_capacity='$engine_capacity', image='$image' WHERE id='$id'";
                } else {
                    $errors[] = "Error uploading image. Make sure it is a valid image file.";
                }
            } else {
                // If no new image is uploaded, retain the old image
                $query = "SELECT image FROM bikes WHERE id='$id'";
                $result = mysqli_query($conn, $query);
                if ($result && $row = mysqli_fetch_assoc($result)) {
                    $image = $row['image'];
                }
                $query = "UPDATE bikes SET brand_id='$brand_id', name='$name', category='$category', price='$price', mileage='$mileage', engine_capacity='$engine_capacity', image='$image' WHERE id='$id'";
            }

            if (empty($errors) && mysqli_query($conn, $query)) {
                echo "<p class='success'>Bike updated successfully!</p>";
            } else {
                echo "<p class='error'>Error: " . mysqli_error($conn) . "</p>";
            }
        } else {
            foreach ($errors as $error) {
                echo "<p class='error'>$error</p>";
            }
        }
    }
}

// Handle deleting a bike
if (isset($_GET['delete'])) {
    $id = !empty($_GET['delete']) ? mysqli_real_escape_string($conn, $_GET['delete']) : '';
    $query = "SELECT image FROM bikes WHERE id='$id'";
    $result = mysqli_query($conn, $query);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $imagePath = "../assets/images/" . $row['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath); // Delete the image file
        }
    }
    $query = "DELETE FROM bikes WHERE id='$id'";
    if (mysqli_query($conn, $query)) {
        echo "<p class='success'>Bike deleted successfully!</p>";
    } else {
        echo "<p class='error'>Error: " . mysqli_error($conn) . "</p>";
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin_login.php'); // Redirect to admin login page after logout
    exit();
}

// Fetch bike details for editing
if (isset($_GET['edit'])) {
    $edit_id = mysqli_real_escape_string($conn, $_GET['edit']);
    $query = "SELECT * FROM bikes WHERE id='$edit_id'";
    $result = mysqli_query($conn, $query);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $brand_id = $row['brand_id'];
        $name = $row['name'];
        $category = $row['category'];
        $price = $row['price'];
        $mileage = $row['mileage'];
        $engine_capacity = $row['engine_capacity'];
        $image = $row['image'];
    } else {
        echo "<p class='error'>Bike not found.</p>";
    }
}

// Fetch bikes for display
$query = "SELECT * FROM bikes";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        /* Add your custom styles here */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #fef3e2;
            color: #161606;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #bec6a0;
            padding: 20px;
            color: #161606;
            text-align: center;
            border-bottom: 2px solid #708870;
        }
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }
        .logo {
            background-color: #708870;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }
        .nav-menu {
            display: flex;
            gap: 10px;
        }
        .nav-menu a {
            color: #161606;
            text-decoration: none;
            font-size: 18px;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .nav-menu a:hover {
            background-color: #708870;
            color: #fff;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #708870;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 2px solid #bec6a0;
            border-radius: 5px;
        }
        .form-group input[type="file"] {
            padding: 5px;
        }
        .form-group .error {
            color: red;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            background-color: #708870;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #bec6a0;
        }
        .btn-danger {
            background-color: #d9534f;
            color: #fff;
        }
        .btn-danger:hover {
            background-color: #c9302c;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #bec6a0;
        }
        table th {
            background-color: #bec6a0;
            color: #161606;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .image-preview {
            max-width: 100px;
            height: auto;
        }
    </style>
</head>
<body>

<header>
    <div class="header-content">
        <a href="admin_panel.php" class="logo">Admin Panel</a>
        <nav class="nav-menu">
            <a href="dashboard.php">Dashboard</a>
            <a href="add_bike.php">Add Bike</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>
</header>

<div class="container">
    <h1><?php echo isset($_GET['edit']) ? 'Edit Bike' : 'Add Bike'; ?></h1>

    <form action="" method="POST" enctype="multipart/form-data">
        <?php if (isset($_GET['edit'])): ?>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($edit_id); ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="brand_id">Brand ID:</label>
            <input type="text" name="brand_id" value="<?php echo htmlspecialchars($brand_id); ?>" required>
        </div>

        <div class="form-group">
            <label for="name">Bike Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
        </div>

        <div class="form-group">
            <label for="category">Category:</label>
            <select name="category" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo ($category == $cat) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" name="price" value="<?php echo htmlspecialchars($price); ?>" required>
        </div>

        <div class="form-group">
            <label for="mileage">Mileage (km/l):</label>
            <input type="number" name="mileage" value="<?php echo htmlspecialchars($mileage); ?>" required>
        </div>

        <div class="form-group">
            <label for="engine_capacity">Engine Capacity (cc):</label>
            <input type="number" name="engine_capacity" value="<?php echo htmlspecialchars($engine_capacity); ?>" required>
        </div>

        <div class="form-group">
            <label for="image">Bike Image:</label>
            <?php if (isset($_GET['edit']) && !empty($image)): ?>
                <img src="../assets/images/<?php echo htmlspecialchars($image); ?>" alt="Bike Image" class="image-preview">
            <?php endif; ?>
            <input type="file" name="image" accept="image/*">
        </div>

        <div class="form-group">
            <input type="submit" name="<?php echo isset($_GET['edit']) ? 'edit_bike' : 'add_bike'; ?>" value="<?php echo isset($_GET['edit']) ? 'Update Bike' : 'Add Bike'; ?>" class="btn">
        </div>
    </form>

    <hr>

    <h1>Manage Bikes</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Brand ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Mileage</th>
                <th>Engine Capacity</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['brand_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                    <td><?php echo htmlspecialchars($row['price']); ?></td>
                    <td><?php echo htmlspecialchars($row['mileage']); ?></td>
                    <td><?php echo htmlspecialchars($row['engine_capacity']); ?></td>
                    <td><img src="../assets/images/<?php echo htmlspecialchars($row['image']); ?>" alt="Bike Image" class="image-preview"></td>
                    <td>
                        <a href="admin_panel.php?edit=<?php echo htmlspecialchars($row['id']); ?>" class="btn">Edit</a>
                        <a href="admin_panel.php?delete=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this bike?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
