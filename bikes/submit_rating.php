<?php
session_start();
include('../includes/connection.php');

$response = array('success' => false, 'message' => 'Unknown error.');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $bikeId = isset($_POST['bike']) ? intval($_POST['bike']) : 0;
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;

    if ($bikeId && $rating >= 1 && $rating <= 5) {
        // Check if the user has already rated this bike
        $query = "SELECT * FROM bike_ratings WHERE bike_id = ? AND user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $bikeId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update existing rating
            $updateQuery = "UPDATE bike_ratings SET rating = ? WHERE bike_id = ? AND user_id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param('iii', $rating, $bikeId, $userId);
            $updateStmt->execute();

            if ($updateStmt->affected_rows > 0) {
                $response['success'] = true;
                $response['message'] = 'Rating updated successfully.';
            } else {
                $response['message'] = 'Failed to update rating.';
            }
        } else {
            // Insert new rating
            $insertQuery = "INSERT INTO bike_ratings (bike_id, user_id, rating) VALUES (?, ?, ?)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param('iii', $bikeId, $userId, $rating);
            $insertStmt->execute();

            if ($insertStmt->affected_rows > 0) {
                $response['success'] = true;
                $response['message'] = 'Rating submitted successfully.';
            } else {
                $response['message'] = 'Failed to submit rating.';
            }
        }

        // Calculate the new average rating for the bike
        $avgQuery = "SELECT AVG(rating) as avg_rating FROM bike_ratings WHERE bike_id = ?";
        $avgStmt = $conn->prepare($avgQuery);
        $avgStmt->bind_param('i', $bikeId);
        $avgStmt->execute();
        $avgResult = $avgStmt->get_result();
        $avgRow = $avgResult->fetch_assoc();
        $avgRating = round($avgRow['avg_rating'], 2);

        // Update the average rating in the bikes table
        $updateBikeQuery = "UPDATE bikes SET rating = ? WHERE id = ?";
        $updateBikeStmt = $conn->prepare($updateBikeQuery);
        $updateBikeStmt->bind_param('di', $avgRating, $bikeId);
        $updateBikeStmt->execute();
    } else {
        $response['message'] = 'Invalid bike ID or rating.';
    }
} else {
    $response['message'] = 'Unauthorized or invalid request.';
}

header('Content-Type: application/json');
echo json_encode($response);
?>
