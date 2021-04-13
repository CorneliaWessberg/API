<?php 
include("../../config/databaseConnection.php");
include("../../objects/Products.php");


$product = new Products($pdo);

if(!empty($_GET['id'])) {
    $product->specifikProduct($_GET['id']);
}


?> 