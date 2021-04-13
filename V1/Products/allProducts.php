<?php
include("../../config/databaseConnection.php");
include("../../objects/Products.php");


$product = new Products($pdo);
$products = $product->showProducts();



?>