<?php
include('../includes/connection.php');

$category = mysqli_real_escape_string($conn, $_GET['category']);
$price = mysqli_real_escape_string($conn, $_GET['price']);

$query = "SELECT * FROM bikes WHERE 1=1";

if ($category) {
    $query .= " AND category = '$category'";
}

if ($price) {
    switch ($price) {
        case 'below_300000':
            $query .= " AND price < 300000";
            break;
        case '300000_600000':
            $query .= " AND price BETWEEN 300000 AND 600000";
            break;
        case 'above_600000':
            $query .= " AND price > 600000";
            break;
    }
}

$result = mysqli_query($conn, $query);

$bikes = array();
while ($bike = mysqli_fetch_assoc($result)) {
    $bikes[] = $bike;
}

echo json_encode($bikes);
?>
