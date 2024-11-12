<?php
include_once '../includes/recommendation.php';
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Get combined recommendations for the logged-in user
    $recommendedBikes = getCombinedRecommendations($userId);
} else {
    header('Location: ../auth/login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recommended Bikes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fef3e2;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #161606;
        }

        .recommendations-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .bike-card {
            background-color: #bec6a0;
            border-radius: 10px;
            width: 300px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .bike-card img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .bike-card h2 {
            color: #161606;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .bike-card p {
            color: #708870;
            font-size: 18px;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>

<h1>Recommended Bikes for You</h1>

<div class="recommendations-container">
    <?php if (count($recommendedBikes) > 0): ?>
        <?php foreach ($recommendedBikes as $bike): ?>
            <div class="bike-card">
                <img src="../assets/images/<?php echo $bike['image']; ?>" alt="<?php echo $bike['name']; ?>">
                <h2><?php echo $bike['name']; ?></h2>
                <p>Category: <?php echo $bike['category']; ?></p>
                <p>Engine Capacity: <?php echo $bike['engine_capacity']; ?>cc</p>
                <p>Price: <?php echo $bike['price']; ?> NPR</p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No recommendations available at this time.</p>
    <?php endif; ?>
</div>

</body>
</html>
