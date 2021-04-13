<?php
include("../../config/databaseConnection.php");
include("../../objects/Products.php");


$title = $_GET['title'];
$description = $_GET['description'];
$image = $_GET['image'];
$price = $_GET['price'];
$category = $_GET['category'];

$product = new Products($pdo);
$product->newProduct($title, $description, $image, $price, $category);

?>