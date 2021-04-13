<?php
include("../../config/databaseConnection.php");
include("../../objects/Products.php");


$title = "";
$description = ""; 
$image = "";
$price = ""; 
$category = "";

if (isset($_GET['id'])){
    $id = $_GET['id'];
}else {
    $error = new stdClass();
    $error->message = "Couldn't find a product with this id, please try again!";
    $error->code = "0012";
    print_r(json_encode($error));
    die();
}

if(isset($_GET['title'])){
    $title = $_GET['title'];
}

if(isset($_GET['description'])) {
    $description = $_GET['description'];
}

if(isset($_GET['image'])) {
    $image = $_GET['image'];
}

if(isset($_GET['price'])) {
    $price = $_GET['price'];
}

if(isset($_GET['category'])) {
    $category = $_GET['category']; 
}

$product = new Products($pdo);
print_r(json_encode($product->updateProduct($id, $title, $description, $image, $price, $category)));
?>