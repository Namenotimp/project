<?php
// Include the header (session_start is inside header.php)
include('includes/header.php');

// Include database connection
include('includes/connection.php');

// Initialize query
$query = "SELECT * FROM bikes WHERE 1=1"; // Base query to fetch all bikes

// Handle filters from the request
if (isset($_GET['category']) && $_GET['category'] !== '') {
    $category = $conn->real_escape_string($_GET['category']);
    $query .= " AND category='$category'";
}

if (isset($_GET['price_range']) && $_GET['price_range'] !== '') {
    $priceRange = $_GET['price_range'];
    if ($priceRange === 'below_300000') {
        $query .= " AND price < 300000";
    } elseif ($priceRange === '300000_600000') {
        $query .= " AND price BETWEEN 300000 AND 600000";
    } elseif ($priceRange === 'above_600000') {
        $query .= " AND price > 600000";
    }
}

if (isset($_GET['min_cc']) && isset($_GET['max_cc'])) {
    $minCC = (int)$_GET['min_cc'];
    $maxCC = (int)$_GET['max_cc'];
    $query .= " AND engine_capacity BETWEEN $minCC AND $maxCC";
}

if (isset($_GET['min_mileage']) && isset($_GET['max_mileage'])) {
    $minMileage = (float)$_GET['min_mileage'];
    $maxMileage = (float)$_GET['max_mileage'];
    $query .= " AND mileage BETWEEN $minMileage AND $maxMileage";
}

// Execute the query
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #343a40;
        }
        #results {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .bike-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            width: 300px; /* Adjusted width for better presentation */
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-bottom: 20px; /* Added margin for spacing */
        }
        .bike-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25);
        }
        .bike-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-bottom: 3px solid #007bff; /* Blue bottom border for accent */
        }
        .bike-card h2 {
            font-size: 1.5em;
            margin: 10px 0;
            color: #007bff; /* Heading color */
        }
        .bike-card p {
            margin: 5px 0;
            color: #495057; /* Text color */
        }
        .no-results {
            text-align: center;
            font-size: 1.5em;
            color: #6c757d;
            margin-top: 20px;
        }
        /* Responsive design */
        @media (max-width: 768px) {
            #results {
                flex-direction: column;
                align-items: center;
            }
            .bike-card {
                width: 90%; /* Full width on smaller screens */
            }
        }
    </style>
</head>
<body>
<main>
    <h1>Search Results</h1>
    <div id="results">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($bike = $result->fetch_assoc()): ?>
                <div class="bike-card">
                    <img src="assets/images/<?php echo htmlspecialchars($bike['image']); ?>" alt="<?php echo htmlspecialchars($bike['name']); ?>">
                    <h2><?php echo htmlspecialchars($bike['name']); ?></h2>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($bike['category']); ?></p>
                    <p><strong>Price:</strong> <?php echo htmlspecialchars($bike['price']); ?> NPR</p>
                    <p><strong>Engine Capacity:</strong> <?php echo htmlspecialchars($bike['engine_capacity']); ?> CC</p>
                    <p><strong>Mileage:</strong> <?php echo htmlspecialchars($bike['mileage']); ?> km/l</p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-results">No bikes found matching your criteria.</p>
        <?php endif; ?>
    </div>
</main>

<!-- Include Footer -->
<?php include('includes/footer.php'); ?>
</body>
</html>
