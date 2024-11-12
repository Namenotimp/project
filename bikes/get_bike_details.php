<?php
include('../includes/connection.php');

$bikeId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($bikeId > 0) {
    $query = "SELECT * FROM bikes WHERE id = $bikeId";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $bike = mysqli_fetch_assoc($result);
        echo json_encode($bike);
    } else {
        echo json_encode(['error' => 'Bike not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid bike ID']);
}
?>
