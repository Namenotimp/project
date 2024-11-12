<?php
include_once '../includes/connection.php';

// Function to get similar bikes based on features
function getContentBasedRecommendations($bikeId, $userId) {
    global $conn;

    // Step 1: Get the features of the bike the user interacted with
    $bikeQuery = "SELECT * FROM bikes WHERE id = ?";
    $stmt = $conn->prepare($bikeQuery);
    $stmt->bind_param("i", $bikeId);
    $stmt->execute();
    $result = $stmt->get_result();
    $bike = $result->fetch_assoc();

    // Step 2: Find bikes with similar features (content-based)
    $similarBikesQuery = "SELECT * FROM bikes WHERE category = ? AND engine_capacity BETWEEN ? AND ? AND price BETWEEN ? AND ?";
    $stmt = $conn->prepare($similarBikesQuery);
    $stmt->bind_param("siiii", $bike['category'], $bike['engine_capacity'] - 50, $bike['engine_capacity'] + 50, $bike['price'] - 50000, $bike['price'] + 50000);
    $stmt->execute();
    $similarBikesResult = $stmt->get_result();

    $similarBikes = [];
    while ($row = $similarBikesResult->fetch_assoc()) {
        $similarBikes[] = $row;
    }

    return $similarBikes;
}

// Function to get collaborative filtering recommendations
function getCollaborativeRecommendations($userId) {
    global $conn;

    // Step 3: Find other users who rated the same bikes
    $collaborativeQuery = "
        SELECT b.*, AVG(br.rating) as avg_rating
        FROM bike_ratings br
        JOIN bikes b ON br.bike_id = b.id
        WHERE br.bike_id IN (SELECT bike_id FROM bike_ratings WHERE user_id = ?)
        AND br.user_id != ?
        GROUP BY br.bike_id
        ORDER BY avg_rating DESC LIMIT 5";
    $stmt = $conn->prepare($collaborativeQuery);
    $stmt->bind_param("ii", $userId, $userId);
    $stmt->execute();
    $collaborativeResult = $stmt->get_result();

    $collaborativeBikes = [];
    while ($row = $collaborativeResult->fetch_assoc()) {
        $collaborativeBikes[] = $row;
    }

    return $collaborativeBikes;
}

// Combined recommendation function
function getCombinedRecommendations($userId) {
    global $conn;

    // Get user's last interacted bike (for content-based filtering)
    $lastInteractedBikeQuery = "SELECT bike_id FROM bike_ratings WHERE user_id = ? ORDER BY id DESC LIMIT 1";
    $stmt = $conn->prepare($lastInteractedBikeQuery);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $lastInteractedBike = $result->fetch_assoc();

    if ($lastInteractedBike) {
        // Get content-based recommendations
        $contentBasedRecommendations = getContentBasedRecommendations($lastInteractedBike['bike_id'], $userId);
    } else {
        $contentBasedRecommendations = [];
    }

    // Get collaborative filtering recommendations
    $collaborativeRecommendations = getCollaborativeRecommendations($userId);

    // Combine the two results (simple merging here, could be improved with ranking)
    return array_merge($contentBasedRecommendations, $collaborativeRecommendations);
}
