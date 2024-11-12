<?php
include('../includes/connection.php');

// Fetch existing bike details if ID is set
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $brand_id = mysqli_real_escape_string($conn, $_POST['brand_id']);
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $price = mysqli_real_escape_string($conn, $_POST['price']);
        $mileage = mysqli_real_escape_string($conn, $_POST['mileage']);
        $engine_capacity = mysqli_real_escape_string($conn, $_POST['engine_capacity']);
        $category = mysqli_real_escape_string($conn, $_POST['category']);

        // Handle Image Upload
        $image = '';
        if (!empty($_FILES['image']['name'])) {
            $image = $_FILES['image']['name'];
            $target = "../assets/images/" . basename($image);

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                echo "<p>Image uploaded successfully!</p>";
            } else {
                echo "<p>Error moving uploaded file.</p>";
            }
        } else {
            // Keep the old image if no new image is uploaded
            $query = "SELECT image FROM bikes WHERE id='$id'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $image = $row['image'];
        }

        // Update bike details
        $query = "UPDATE bikes SET brand_id='$brand_id', name='$name', price='$price', mileage='$mileage', engine_capacity='$engine_capacity', category='$category', image='$image' WHERE id='$id'";

        if (mysqli_query($conn, $query)) {
            // Redirect to view_bikes.php after a successful update
            header("Location: view_bikes.php");
            exit();
        } else {
            echo "<p>Error: " . mysqli_error($conn) . "</p>";
        }
    }

    $query = "SELECT * FROM bikes WHERE id='$id'";
    $result = mysqli_query($conn, $query);
    $bike = mysqli_fetch_assoc($result);
} else {
    echo "<p>Invalid bike ID.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bike</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fef3e2;
            color: #161606;
            margin: 0;
            padding: 20px;
        }
        h2 {
            color: #708870;
        }
        form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 5px 0 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        button {
            background-color: #708870;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #606e5f;
        }
        img {
            max-width: 150px;
            height: auto;
            display: block;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h2>Edit Existing Bike</h2>
    <form action="edit_bike.php?id=<?php echo htmlspecialchars($id); ?>" method="post" enctype="multipart/form-data">
        <label for="brand_id">Brand ID:</label>
        <input type="text" id="brand_id" name="brand_id" value="<?php echo htmlspecialchars($bike['brand_id']); ?>" required>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($bike['name']); ?>" required>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($bike['price']); ?>" required>

        <label for="mileage">Mileage:</label>
        <input type="number" id="mileage" name="mileage" step="0.01" value="<?php echo htmlspecialchars($bike['mileage']); ?>" required>

        <label for="engine_capacity">Engine Capacity (cc):</label>
        <input type="number" id="engine_capacity" name="engine_capacity" value="<?php echo htmlspecialchars($bike['engine_capacity']); ?>" required>

        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="Sport" <?php if ($bike['category'] == 'Sport') echo 'selected'; ?>>Sport</option>
            <option value="Cruiser" <?php if ($bike['category'] == 'Cruiser') echo 'selected'; ?>>Cruiser</option>
            <option value="Street" <?php if ($bike['category'] == 'Street') echo 'selected'; ?>>Street</option>
            <option value="Commuter" <?php if ($bike['category'] == 'Commuter') echo 'selected'; ?>>Commuter</option>
            <option value="Scooter" <?php if ($bike['category'] == 'Scooter') echo 'selected'; ?>>Scooter</option>
        </select>

        <label for="image">Image:</label>
        <input type="file" id="image" name="image" accept="image/*">
        <?php if (!empty($bike['image'])): ?>
            <img src="../assets/images/<?php echo htmlspecialchars($bike['image']); ?>" alt="<?php echo htmlspecialchars($bike['name']); ?>">
        <?php endif; ?>

        <button type="submit">Update Bike</button>
    </form>
</body>
</html>
