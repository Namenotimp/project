<?php
session_start();
include('../includes/connection.php');

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
}

if(isset($_POST['add_bike'])){
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $price = $_POST['price'];
    $mileage = $_POST['mileage'];
    $cc = $_POST['cc'];
    $category = $_POST['category'];
    $image = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/$image");

    $query = "INSERT INTO bikes (brand, model, price, mileage, cc, category, image) VALUES ('$brand', '$model', '$price', '$mileage', '$cc', '$category', '$image')";
    mysqli_query($conn, $query);
}
?>

<h1>Admin Dashboard</h1>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="brand" placeholder="Brand" required>
    <input type="text" name="model" placeholder="Model" required>
    <input type="number" name="price" placeholder="Price" required>
    <input type="number" name="mileage" placeholder="Mileage (km/l)" required>
    <input type="number" name="cc" placeholder="CC" required>
    <input type="text" name="category" placeholder="Category" required>
    <input type="file" name="image" required>
    <button type="submit" name="add_bike">Add Bike</button>
</form>
