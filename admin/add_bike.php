<?php
include('../includes/connection.php');

// Fetch categories for the dropdown
$categories = [];
$categoryQuery = "SELECT DISTINCT category FROM bikes";
$categoryResult = mysqli_query($conn, $categoryQuery);
if ($categoryResult) {
    while ($row = mysqli_fetch_assoc($categoryResult)) {
        $categories[] = $row['category'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_bike'])) {
    $brand_id = mysqli_real_escape_string($conn, $_POST['brand_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $mileage = mysqli_real_escape_string($conn, $_POST['mileage']);
    $engine_capacity = mysqli_real_escape_string($conn, $_POST['engine_capacity']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    // Handle Image Upload
    $image = $_FILES['image']['name'];
    $target = "../assets/images/" . basename($image);
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $query = "INSERT INTO bikes (brand_id, name, price, mileage, engine_capacity, category, image) 
                  VALUES ('$brand_id', '$name', '$price', '$mileage', '$engine_capacity', '$category', '$image')";

        if (mysqli_query($conn, $query)) {
            echo "<p class='success'>Bike added successfully!</p>";
        } else {
            echo "<p class='error'>Error: " . mysqli_error($conn) . "</p>";
        }
    } else {
        echo "<p class='error'>Error uploading image.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Bike</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fef3e2;
            color: #161606;
            margin: 0;
            padding: 0;
        }
        h2 {
            background-color: #708870;
            color: #fff;
            padding: 10px;
            margin: 0;
        }
        form {
            margin: 20px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
        }
        input, select, button {
            margin-bottom: 12px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 100%;
        }
        button {
            background-color: #708870;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #5a6b56;
        }
        .error {
            color: #e74c3c;
            margin: 20px;
        }
        .success {
            color: #2ecc71;
            margin: 20px;
        }
    </style>
</head>
<body>
    <h2>Add New Bike</h2>
    <form action="add_bike.php" method="post" enctype="multipart/form-data">
        <label for="brand_id">Brand ID:</label>
        <input type="text" id="brand_id" name="brand_id" required>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></option>
            <?php endforeach; ?>
        </select>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" required>

        <label for="mileage">Mileage:</label>
        <input type="number" id="mileage" name="mileage" step="0.01" required>

        <label for="engine_capacity">Engine Capacity (cc):</label>
        <input type="number" id="engine_capacity" name="engine_capacity" required>

        <label for="image">Image:</label>
        <input type="file" id="image" name="image" accept="image/*" required>

        <button type="submit" name="add_bike">Add Bike</button>
    </form>
</body>
</html>
