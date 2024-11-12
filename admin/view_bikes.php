<?php
include('../includes/connection.php');

// Delete bike
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    
    // Fetch the image path
    $query = "SELECT image FROM bikes WHERE id='$id'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if ($row['image']) {
            $image_path = "../assets/images/" . $row['image'];
            if (file_exists($image_path)) {
                unlink($image_path); // Delete the image file
            }
        }
    }
    
    // Delete the bike record
    $query = "DELETE FROM bikes WHERE id='$id'";
    if (mysqli_query($conn, $query)) {
        echo "<p class='success'>Bike deleted successfully.</p>";
    } else {
        echo "<p class='error'>Error deleting bike: " . mysqli_error($conn) . "</p>";
    }
}

// Fetch bikes
$query = "SELECT * FROM bikes";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bikes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fef3e2;
            color: #161606;
            margin: 0;
            padding: 0;
        }
        h1 {
            background-color: #708870;
            color: #fff;
            padding: 10px;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #708870;
            color: #fff;
        }
        img {
            max-width: 100px;
            height: auto;
        }
        .actions a {
            color: #708870;
            text-decoration: none;
            padding: 5px;
        }
        .actions a:hover {
            color: #161606;
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
    <h1>View Bikes</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Brand</th>
            <th>Model</th>
            <th>Price</th>
            <th>Mileage</th>
            <th>Engine Capacity</th>
            <th>Category</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        <?php while ($bike = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo htmlspecialchars($bike['id']); ?></td>
            <td><?php echo htmlspecialchars($bike['name']); ?></td>
            <td><?php echo htmlspecialchars($bike['brand_id']); ?></td>
            <td><?php echo number_format($bike['price'], 2); ?> NPR</td>
            <td><?php echo htmlspecialchars($bike['mileage']); ?> km/l</td>
            <td><?php echo htmlspecialchars($bike['engine_capacity']); ?> cc</td>
            <td><?php echo htmlspecialchars($bike['category']); ?></td>
            <td>
                <?php if (!empty($bike['image'])): ?>
                    <img src="../assets/images/<?php echo htmlspecialchars($bike['image']); ?>" alt="<?php echo htmlspecialchars($bike['name']); ?>">
                <?php else: ?>
                    No Image
                <?php endif; ?>
            </td>
            <td class="actions">
                <a href="edit_bike.php?id=<?php echo htmlspecialchars($bike['id']); ?>">Edit</a> |
                <a href="view_bikes.php?delete=<?php echo htmlspecialchars($bike['id']); ?>" onclick="return confirm('Are you sure you want to delete this bike?');">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
