<?php
include('../includes/connection.php');
include('../includes/header.php');

// Fetch search query from URL parameters
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Updated query to include search functionality
$query = "SELECT bikes.*, brands.name AS brand_name FROM bikes
          JOIN brands ON bikes.brand_id = brands.id
          WHERE bikes.name LIKE '%$search%' 
             OR brands.name LIKE '%$search%' 
             OR bikes.category LIKE '%$search%'";
$result = mysqli_query($conn, $query);

// Check if any results were found
if (mysqli_num_rows($result) == 0):
?>

<main>
    <h1>No Results Found</h1>
    <p>Sorry, no bikes match your search criteria. Please try a different search term.</p>
    <a href="bikes-list.php">Back to bike list</a>
</main>

<?php else: ?>

<main>
    <h1>Bike List</h1>
    <form method="GET" action="">
        <input type="text" id="search" name="search" placeholder="Search bikes..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
    </form>

    <div class="bike-grid" id="bike-list">
        <?php while($bike = mysqli_fetch_assoc($result)): ?>
            <div class="bike-card" data-brand="<?php echo strtolower($bike['brand_name']); ?>" data-category="<?php echo strtolower($bike['category']); ?>">
                <img src="../assets/images/<?php echo $bike['image']; ?>" alt="<?php echo $bike['name']; ?>">
                <h3><?php echo $bike['brand_name'] . " " . $bike['name']; ?></h3>
                <p>Price: <?php echo number_format($bike['price'], 2); ?> NPR</p>
                <p>Mileage: <?php echo $bike['mileage']; ?> km/l</p>
                <p>Engine Capacity: <?php echo $bike['engine_capacity']; ?> cc</p>
                <p>Category: <?php echo $bike['category']; ?></p>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Modal -->
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

<?php endif; ?>

<?php include('../includes/footer.php'); ?>
<script src="../js/scripts.js"></script>
