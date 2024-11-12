<?php
include('includes/connection.php'); // Ensure this path is correct
session_start();

$bike_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Fetch bike details
$query = "SELECT * FROM bikes WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $bike_id);
$stmt->execute();
$bike = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $user_id) {
    $rating = intval($_POST['rating']);

    // Check if the user has already rated this bike
    $query = "SELECT * FROM ratings WHERE user_id = ? AND bike_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $bike_id);
    $stmt->execute();
    $existing_rating = $stmt->get_result()->fetch_assoc();

    if ($existing_rating) {
        // Update existing rating
        $query = "UPDATE ratings SET rating = ? WHERE user_id = ? AND bike_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iii", $rating, $user_id, $bike_id);
    } else {
        // Insert new rating
        $query = "INSERT INTO ratings (user_id, bike_id, rating) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iii", $user_id, $bike_id, $rating);
    }

    $stmt->execute();
    echo "<p>Thank you for rating!</p>";
}

// Fetch average rating
$query = "SELECT AVG(rating) as avg_rating FROM ratings WHERE bike_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $bike_id);
$stmt->execute();
$rating_result = $stmt->get_result()->fetch_assoc();
$average_rating = number_format($rating_result['avg_rating'], 1);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bike Details</title>
</head>
<body>
    <h1><?php echo htmlspecialchars($bike['name']); ?></h1>
    <p>Average Rating: <?php echo $average_rating; ?>/5</p>

    <?php if ($user_id): ?>
        <form method="post" action="">
            <label for="rating">Rate this bike (1-5):</label>
            <select name="rating" id="rating">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <button type="submit">Submit Rating</button>
        </form>
    <?php else: ?>
        <p>Please <a href="auth/login.php">login</a> to rate this bike.</p>
    <?php endif; ?>
</body>
</html>
