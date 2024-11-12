document.addEventListener('DOMContentLoaded', () => {
    // Form submission handler
    document.getElementById('searchForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        // Get search input value
        const searchValue = document.getElementById('search').value;

        // Redirect to search results with query string
        window.location.href = `index.php?search=${encodeURIComponent(searchValue)}`;
    });

    // Add event listeners for bike cards to open modal
    document.querySelectorAll('.bike-card').forEach(card => {
        card.addEventListener('click', () => {
            const bikeId = card.getAttribute('data-id');
            fetchBikeDetails(bikeId);
        });
    });
});

function fetchBikeDetails(bikeId) {
    fetch(`../bikes/get_bike_details.php?id=${bikeId}`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                document.getElementById('modalTitle').textContent = data.name;
                document.getElementById('modalImage').src = `../assets/images/${data.image}`;
                document.getElementById('modalPrice').textContent = `Price: ${data.price} NPR`;
                document.getElementById('modalMileage').textContent = `Mileage: ${data.mileage} km/l`;
                document.getElementById('modalEngineCapacity').textContent = `Engine Capacity: ${data.engine_capacity} cc`;
                document.getElementById('modalCategory').textContent = `Category: ${data.category}`;
                openModal();
            }
        });
}

function openModal() {
    document.getElementById('bikeModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('bikeModal').style.display = 'none';
}




// Function to show bike details in a modal
function showBikeDetails(id) {
    const modal = document.getElementById('bikeModal');
    const title = document.getElementById('modalTitle');
    const image = document.getElementById('modalImage');
    const price = document.getElementById('modalPrice');
    const mileage = document.getElementById('modalMileage');
    const engineCapacity = document.getElementById('modalEngineCapacity');
    const category = document.getElementById('modalCategory');
    
    fetch(`bikes/get_bike_details.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            title.textContent = `${data.brand} ${data.model}`;
            image.src = `../assets/images/${data.image}`;
            price.textContent = `Price: ${data.price} NPR`;
            mileage.textContent = `Mileage: ${data.mileage} km/l`;
            engineCapacity.textContent = `Engine Capacity: ${data.engine_capacity} cc`;
            category.textContent = `Category: ${data.category}`;
            modal.style.display = 'block';
        });
}

// Function to close the modal
function closeModal() {
    document.getElementById('bikeModal').style.display = 'none';
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    if (event.target === document.getElementById('bikeModal')) {
        closeModal();
    }
}

// Handle form submission for filtering bikes
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('searchForm');

    if (form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            const category = document.getElementById('category').value;
            const price = document.getElementById('price').value;

            fetch(`bikes/filter_bikes.php?category=${category}&price=${price}`)
                .then(response => response.json())
                .then(data => {
                    const bikeList = document.getElementById('bike-list');
                    bikeList.innerHTML = ''; // Clear previous results
                    
                    data.forEach(bike => {
                        bikeList.innerHTML += `
                            <div class="bike-card" data-brand="${bike.brand.toLowerCase()}" data-category="${bike.category.toLowerCase()}">
                                <img src="../assets/images/${bike.image}" alt="${bike.model}">
                                <h3>${bike.brand} ${bike.model}</h3>
                                <p>Price: ${bike.price} NPR</p>
                                <p>Mileage: ${bike.mileage} km/l</p>
                                <p>Engine Capacity: ${bike.engine_capacity} cc</p>
                                <p>Category: ${bike.category}</p>
                            </div>
                        `;
                    });
                })
                .catch(error => console.error('Error:', error));
        });
    }

    // Attach the search functionality to the keyup event
    document.getElementById('search').addEventListener('keyup', searchBikes);
});
