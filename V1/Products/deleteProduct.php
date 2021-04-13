<?php
include("../../config/databaseConnection.php");
include("../../objects/Products.php");

$product = new Products($pdo);

if(!empty($_GET['id'])) {
    $product->deleteProduct($_GET['id']);

}else {
    $error = new stdClass();
    $error->message = "Couldn't find the id for the product, please try again!";
    $error->code = "0010";
    print_r(json_encode($error));
    die();
}

?>