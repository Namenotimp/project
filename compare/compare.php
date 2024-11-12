<?php
// Include the database connection
include('../includes/connection.php');

// Fetch selected bikes' IDs from query parameters
$bike1_id = isset($_GET['bike1']) ? intval($_GET['bike1']) : 0;
$bike2_id = isset($_GET['bike2']) ? intval($_GET['bike2']) : 0;

// Validate bike IDs
if ($bike1_id <= 0 || $bike2_id <= 0) {
    echo 'Invalid bike selection.';
    exit;
}

// Fetch details of the selected bikes
$query = "SELECT * FROM bikes WHERE id IN (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $bike1_id, $bike2_id);
$stmt->execute();
$result = $stmt->get_result();
$bikes = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bike Comparison</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #6c757d; /* Muted Gray */
            background-color: #f4f4f4; /* Light Gray */
            margin: 0;
            padding: 0;
        }
        main {
            padding: 20px;
            background-color: #ffffff; /* White */
            margin: 20px auto;
            max-width: 1200px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #007bff; /* Vibrant Blue */
            margin-bottom: 20px;
            text-align: center;
        }
        .comparison-table {
            width: 100%;
            border-collapse: collapse;
        }
        .comparison-table th, .comparison-table td {
            border: 1px solid #dee2e6; /* Light Gray */
            padding: 12px;
            text-align: left;
        }
        .comparison-table th {
            background-color: #007bff; /* Vibrant Blue */
            color: #ffffff; /* White */
        }
        .comparison-table td {
            background-color: #ffffff; /* White */
            color: #6c757d; /* Muted Gray */
        }
        .comparison-table tr:nth-child(even) td {
            background-color: #f9f9f9; /* Very Light Gray */
        }
        .comparison-table tr:hover td {
            background-color: #e2e6ea; /* Light Gray */
        }
    </style>
</head>
<body>

<main>
    <h1>Bike Comparison</h1>
    <?php if (count($bikes) == 2): ?>
        <table class="comparison-table">
            <tr>
                <th>Feature</th>
                <th><?php echo htmlspecialchars($bikes[0]['name']); ?></th>
                <th><?php echo htmlspecialchars($bikes[1]['name']); ?></th>
            </tr>
            <tr>
                <td>Price</td>
                <td><?php echo htmlspecialchars($bikes[0]['price']); ?></td>
                <td><?php echo htmlspecialchars($bikes[1]['price']); ?></td>
            </tr>
            <tr>
                <td>Mileage</td>
                <td><?php echo htmlspecialchars($bikes[0]['mileage']); ?></td>
                <td><?php echo htmlspecialchars($bikes[1]['mileage']); ?></td>
            </tr>
            <tr>
                <td>Engine Capacity</td>
                <td><?php echo htmlspecialchars($bikes[0]['engine_capacity']); ?></td>
                <td><?php echo htmlspecialchars($bikes[1]['engine_capacity']); ?></td>
            </tr>
            <tr>
                <td>Category</td>
                <td><?php echo htmlspecialchars($bikes[0]['category']); ?></td>
                <td><?php echo htmlspecialchars($bikes[1]['category']); ?></td>
            </tr>
            <!-- Add more features as needed -->
        </table>
    <?php else: ?>
        <p>Please select two bikes to compare.</p>
    <?php endif; ?>
</main>

<?php include('../includes/footer.php'); ?>

</body>
</html>
