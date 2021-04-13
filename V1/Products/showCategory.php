<?php 
include("../../config/databaseConnection.php");
include("../../objects/Products.php");



if(isset($_GET['category'])) {
    $category = $_GET['category'];

}

$product = new Products($pdo);
$product->showCategory($category);

?>