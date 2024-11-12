<?php
include('../includes/connection.php');

// Fetch bikes from database for dropdown
$query = "SELECT id, name FROM bikes";
$result = $conn->query($query);
$bikes = $result->fetch_all(MYSQLI_ASSOC);

// Handle search functionality
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Updated query to include average rating calculation
$query = "
    SELECT b.id, b.name, b.brand_id, b.category, b.price, b.mileage, b.engine_capacity, b.image, 
           COALESCE(AVG(br.rating), 0) AS average_rating
    FROM bikes b
    LEFT JOIN bike_ratings br ON b.id = br.bike_id
    WHERE b.name LIKE '%$search%' OR b.category LIKE '%$search%'
    GROUP BY b.id
    ORDER BY average_rating DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bike List</title>
    <style>
        /* Basic reset for margins and paddings */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #6c757d;
        }

        main {
            padding: 20px;
        }

        header {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-container {
            display: inline-block;
        }

        .search-container input[type="text"] {
            padding: 10px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            width: 300px;
        }

        .search-container button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #708870;
            color: #ffffff;
            cursor: pointer;
            margin-left: 10px;
        }

        .search-container button:hover {
            background-color: #5a6f54;
        }

        .bike-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .bike-card {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 250px;
            padding: 15px;
            text-align: center;
            color: #161606;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .bike-card img {
            width: 100%;
            height: auto;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 10px;
        }

        .bike-card h3 {
            margin: 10px 0;
            color: #708870;
        }

        .bike-card p {
            margin: 5px 0;
        }

        .error-message {
            color: #d9534f;
            text-align: center;
            font-size: 1.2em;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: #ffffff;
            border-radius: 5px;
            padding: 20px;
            width: 80%;
            max-width: 600px;
            position: relative;
        }

        .modal img {
            max-width: 100%;
            height: auto;
        }

        .modal .close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 1.5em;
            cursor: pointer;
            color: #161606;
        }

        .modal .close:hover {
            color: #d9534f;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .bike-card {
                width: 100%; /* Full width on smaller screens */
            }
        }
    </style>
</head>
<body>
    <main>
        <header>
            <h1>Bike List</h1>
            <div class="search-container">
                <form id="searchForm" action="" method="GET">
                    <input type="text" id="search" name="search" placeholder="Search bikes..." 
                           value="<?php echo htmlspecialchars($search); ?>" required>
                    <button type="submit">Search</button>
                </form>
            </div>
        </header>

        <section class="bike-grid" id="bike-list">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while($bike = mysqli_fetch_assoc($result)): ?>
                    <article class="bike-card" data-id="<?php echo $bike['id']; ?>" 
                            data-brand="<?php echo strtolower($bike['brand_id']); ?>" 
                            data-category="<?php echo strtolower($bike['category']); ?>">
                        <img src="../assets/images/<?php echo $bike['image']; ?>" 
                             alt="<?php echo htmlspecialchars($bike['name']); ?>">
                        <h3><?php echo htmlspecialchars($bike['name']); ?></h3>
                        <p>Price: <strong><?php echo number_format($bike['price'], 2); ?> NPR</strong></p>
                        <p>Mileage: <?php echo htmlspecialchars($bike['mileage']); ?> km/l</p>
                        <p>Engine Capacity: <?php echo htmlspecialchars($bike['engine_capacity']); ?> cc</p>
                        <p>Category: <?php echo htmlspecialchars($bike['category']); ?></p>
                        <p>Average Rating: <span id="average-rating-<?php echo $bike['id']; ?>">
                            <?php echo number_format($bike['average_rating'], 1); ?></span> ★</p>

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <form class="rating-form" data-bike-id="<?php echo $bike['id']; ?>">
                                <label for="rating-<?php echo $bike['id']; ?>">Rate this bike:</label>
                                <select name="rating" id="rating-<?php echo $bike['id']; ?>">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                                <button type="submit">Submit</button>
                            </form>
                            <div class="rating-message" id="rating-message-<?php echo $bike['id']; ?>"></div>
                        <?php else: ?>
                            <p><em>Log in to rate this bike.</em></p>
                        <?php endif; ?>
                    </article>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="error-message">No bikes found matching your search criteria.</p>
            <?php endif; ?>
        </section>

        <div id="bikeModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2 id="modalTitle"></h2>
                <img id="modalImage" src="" alt="">
                <p id="modalPrice"></p>
                <p id="modalMileage"></p>
                <p id="modalEngineCapacity"></p>
                <p id="modalCategory"></p>
            </div>
        </div>
    </main>

    <script>
        // Function to open modal with bike details
        function openModal(bike) {
            document.getElementById('modalTitle').textContent = bike.querySelector('h3').textContent;
            document.getElementById('modalImage').src = bike.querySelector('img').src;
            document.getElementById('modalPrice').textContent = 'Price: ' + bike.querySelector('p:nth-of-type(1)').textContent;
            document.getElementById('modalMileage').textContent = 'Mileage: ' + bike.querySelector('p:nth-of-type(2)').textContent;
            document.getElementById('modalEngineCapacity').textContent = 'Engine Capacity: ' + bike.querySelector('p:nth-of-type(3)').textContent;
            document.getElementById('modalCategory').textContent = 'Category: ' + bike.querySelector('p:nth-of-type(4)').textContent;
            document.getElementById('bikeModal').style.display = 'flex';
        }

        // Function to close modal
        function closeModal() {
            document.getElementById('bikeModal').style.display = 'none';
        }

        // Event listener for bike cards
        document.querySelectorAll('.bike-card').forEach(card => {
            card.addEventListener('click', () => openModal(card));
        });

        // Handle rating submission
        document.querySelectorAll('.rating-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const bikeId = this.getAttribute('data-bike-id');
                const ratingValue = this.querySelector('select[name="rating"]').value;

                fetch('rate_bike.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ bike_id: bikeId, rating: ratingValue }),
                })
                .then(response => response.json())
                .then(data => {
                    const messageElement = document.getElementById(`rating-message-${bikeId}`);
                    messageElement.textContent = data.message;

                    // Update average rating
                    const avgRatingElement = document.getElementById(`average-rating-${bikeId}`);
                    avgRatingElement.textContent = data.newAverage.toFixed(1);
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>
</body>
</html>
