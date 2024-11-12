<?php
$user_id = $_SESSION['user_id'] ?? null;
$recommendations = [];

if ($user_id) {
    // Get the bikes the user has rated highly
    $query = "SELECT b.id, b.name, b.price, b.engine_capacity, b.mileage, b.category, b.image
              FROM bikes b
              JOIN bike_ratings br ON b.id = br.bike_id
              WHERE br.user_id = ? AND br.rating >= 4";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Create an array of bikes the user has liked
    $liked_bikes = [];
    while ($bike = $result->fetch_assoc()) {
        $liked_bikes[] = $bike;
    }

    if (!empty($liked_bikes)) {
        // Generate recommendations based on similar bikes
        foreach ($liked_bikes as $liked_bike) {
            $query = "SELECT b.id, b.name, b.price, b.engine_capacity, b.mileage, b.category, b.image, 
                             (ABS(b.price - ?) + ABS(b.engine_capacity - ?) + ABS(b.mileage - ?)) AS similarity_score
                      FROM bikes b
                      WHERE b.id != ?
                      ORDER BY similarity_score ASC
                      LIMIT 5";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("dddi", $liked_bike['price'], $liked_bike['engine_capacity'], $liked_bike['mileage'], $liked_bike['id']);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($bike = $result->fetch_assoc()) {
                $recommendations[] = $bike;
            }
        }
    }
}
?>
