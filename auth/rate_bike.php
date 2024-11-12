<?php
session_start();
include('../includes/connection.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to rate a bike.']);
    exit;
}

// Get the user ID from session
$user_id = $_SESSION['user_id'];

// Check if the POST request contains the necessary data
if (isset($_POST['bike_id']) && isset($_POST['rating'])) {
    $bike_id = intval($_POST['bike_id']);
    $rating = intval($_POST['rating']);

    // Validate the rating value (between 1 and 5)
    if ($rating < 1 || $rating > 5) {
        echo json_encode(['success' => false, 'message' => 'Invalid rating value.']);
        exit;
    }

    // Check if the user has already rated this bike
    $checkQuery = "SELECT * FROM bike_ratings WHERE bike_id = ? AND user_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $bike_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If the user has already rated this bike, update the existing rating
        $updateQuery = "UPDATE bike_ratings SET rating = ? WHERE bike_id = ? AND user_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("iii", $rating, $bike_id, $user_id);
        $updateStmt->execute();
    } else {
        // Otherwise, insert a new rating
        $insertQuery = "INSERT INTO bike_ratings (bike_id, user_id, rating) VALUES (?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("iii", $bike_id, $user_id, $rating);
        $insertStmt->execute();
    }

    // Calculate the new average rating for the bike
    $averageQuery = "SELECT AVG(rating) AS average_rating FROM bike_ratings WHERE bike_id = ?";
    $avgStmt = $conn->prepare($averageQuery);
    $avgStmt->bind_param("i", $bike_id);
    $avgStmt->execute();
    $avgResult = $avgStmt->get_result();
    $avgRow = $avgResult->fetch_assoc();
    $average_rating = round($avgRow['average_rating'], 1); // Round to one decimal

    // Update the bikes table with the new average rating
    $updateBikeQuery = "UPDATE bikes SET rating = ? WHERE id = ?";
    $updateBikeStmt = $conn->prepare($updateBikeQuery);
    $updateBikeStmt->bind_param("di", $average_rating, $bike_id); // "d" for double
    $updateBikeStmt->execute();

    // Return success message along with the new average rating
    echo json_encode(['success' => true, 'message' => 'Rating submitted successfully.', 'new_rating' => $average_rating]);

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
