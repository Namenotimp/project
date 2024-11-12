<?php
// Include the header (session_start is inside header.php)
include('includes/header.php');

// Check if a user is logged in (variables should be set in header.php)
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$firstLetter = $username ? strtoupper($username[0]) : '';

// Fetch bikes from the database
include('includes/connection.php');
$query = "SELECT id, name FROM bikes";
$result = $conn->query($query);
$bikes = $result->fetch_all(MYSQLI_ASSOC);

// Fetch ratings for bikes
$ratingQuery = "SELECT bike_id, AVG(rating) as avg_rating FROM bike_ratings GROUP BY bike_id";
$ratingResult = $conn->query($ratingQuery);
$ratings = [];
while ($row = $ratingResult->fetch_assoc()) {
    $ratings[$row['bike_id']] = $row['avg_rating'];
}

function validate_rating($rating) {
    return $rating >= 1 && $rating <= 5;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bike Recommendation</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .header {
            padding: 20px;
            text-align: center;
            background-color: #007bff;
            color: #ffffff;
            border-bottom: 4px solid #0056b3; /* Bottom border for emphasis */
        }

        .header h1 {
            margin: 0;
            font-size: 2em; /* Larger font size for the title */
        }

        .container {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            flex-wrap: wrap; /* Wrap for responsiveness */
        }

        .column {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 10px; /* Uniform margin for spacing */
            flex: 1 1 300px; /* Flex property for responsive columns */
            max-width: 30%; /* Limit width to 30% */
        }

        .column h2 {
            margin-top: 0;
            color: #007bff;
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        select,
        input {
            width: calc(100% - 10px);
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            transition: border-color 0.3s;
        }

        select:focus,
        input:focus {
            border-color: #007bff; /* Focused border color */
        }

        button {
            background-color: #007bff;
            color: #ffffff;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 1em; /* Increased font size for buttons */
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                align-items: center; /* Center align for mobile */
            }
            .column {
                max-width: 90%; /* Full width on smaller screens */
            }
        }
    </style>
</head>
<body>
<main class="header">
    <h1>Get To Know Your Bike</h1>

    <!-- Container for Columns -->
    <div class="container">
        <!-- Search Form Column -->
        <div class="column search-column">
            <h2>Search for Bikes</h2>
            <form id="searchForm" method="GET" action="search_results.php">
                <label for="category">Category:</label>
                <select name="category" id="category">
                    <option value="">All Categories</option>
                    <option value="Commuter">Commuter</option>
                    <option value="Sport">Sport</option>
                    <option value="Cruiser">Cruiser</option>
                    <option value="Scooter">Scooter</option>
                </select>

                <label for="price_range">Price Range:</label>
                <select name="price_range" id="price_range">
                    <option value="">All Prices</option>
                    <option value="below_300000">Below 300,000 NPR</option>
                    <option value="300000_600000">300,000 - 600,000 NPR</option>
                    <option value="above_600000">Above 600,000 NPR</option>
                </select>

                <label for="cc_range">Engine Capacity (CC):</label>
                <input type="number" name="min_cc" id="min_cc" placeholder="Min CC" min="0" step="1" required>
                <input type="number" name="max_cc" id="max_cc" placeholder="Max CC" min="0" step="1" required>

                <label for="mileage_range">Mileage:</label>
                <input type="number" name="min_mileage" id="min_mileage" placeholder="Min Mileage" min="0" step="0.01" required>
                <input type="number" name="max_mileage" id="max_mileage" placeholder="Max Mileage" min="0" step="0.01" required>

                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Compare Form Column -->
        <div class="column compare-column">
            <h2>Compare Bikes</h2>
            <form id="compareForm" method="GET" action="compare/compare.php">
                <label for="bike1">Select Bike 1:</label>
                <select name="bike1" id="bike1" required>
                    <option value="">--Select Bike--</option>
                    <?php foreach ($bikes as $bike): ?>
                        <option value="<?php echo htmlspecialchars($bike['id']); ?>"><?php echo htmlspecialchars($bike['name']); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="bike2">Select Bike 2:</label>
                <select name="bike2" id="bike2" required>
                    <option value="">--Select Bike--</option>
                    <?php foreach ($bikes as $bike): ?>
                        <option value="<?php echo htmlspecialchars($bike['id']); ?>"><?php echo htmlspecialchars($bike['name']); ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">Compare Bikes</button>
            </form>
        </div>

        <!-- Rating Form Column -->
        <?php if ($username): ?>
            <div class="column rate-column">
                <h2>Rate a Bike</h2>
                <form id="ratingForm" method="POST" action="bikes/submit_rating.php">
                    <label for="bike">Select Bike:</label>
                    <select name="bike" id="bike" required>
                        <option value="">--Select Bike--</option>
                        <?php foreach ($bikes as $bike): ?>
                            <option value="<?php echo htmlspecialchars($bike['id']); ?>"><?php echo htmlspecialchars($bike['name']); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label for="rating">Rating (1-5):</label>
                    <input type="number" name="rating" id="rating" min="1" max="5" required>

                    <button type="submit">Submit Rating</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
    // Validate search form before submission
    document.getElementById('searchForm').onsubmit = function () {
        const minCC = parseInt(document.getElementById('min_cc').value);
        const maxCC = parseInt(document.getElementById('max_cc').value);
        const minMileage = parseFloat(document.getElementById('min_mileage').value);
        const maxMileage = parseFloat(document.getElementById('max_mileage').value);

        if (minCC > maxCC) {
            alert("Minimum CC must be less than or equal to Maximum CC.");
            return false;
        }

        if (minMileage > maxMileage) {
            alert("Minimum Mileage must be less than or equal to Maximum Mileage.");
            return false;
        }

        return true; // Valid input
    };

    // Validate compare form before submission
    document.getElementById('compareForm').onsubmit = function () {
        const bike1 = document.getElementById('bike1').value;
        const bike2 = document.getElementById('bike2').value;

        if (bike1 === bike2) {
            alert("You must select two different bikes for comparison.");
            return false;
        }

        return true; // Valid input
    };

    // Validate rating form
    document.getElementById('ratingForm').onsubmit = function () {
        const bike = document.getElementById('bike').value;
        const rating = parseInt(document.getElementById('rating').value);

        if (!bike) {
            alert('Please select a bike.');
            return false;
        }
        if (!rating) {
            alert('Please provide a rating.');
            return false;
        }
        if (rating < 1 || rating > 5) {
            alert('Rating must be between 1 and 5.');
            return false;
        }

        return true; // Valid input
    };
</script>
</body>
</html>
