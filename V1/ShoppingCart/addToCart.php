<?php 
include("../../config/databaseConnection.php");
include("../../objects/Users.php");
include("../../objects/ShoppingCart.php");


$token = "";
if(isset($_GET['token'])) {
    $token = $_GET['token'];
}else {
        $error = new stdClass();
        $error->message = "No token found, please try again!";
        $error->code = "2004";
        print_r(json_encode($error));
        die();
}

$cart = new shoppingCart($pdo); 
$user = new User($pdo);

if($user->validToken($token)) {

    if(isset($_GET['productId'])) {

    $cart->addToCart($_GET['productId'], $_GET['token']);
    $return = new stdClass();
    $return->message = "Product succesfully added to your shoppingcart!";
    print_r(json_encode($return));

}else {

        $error = new stdClass();
        $error->message = "Choose a product please!";
        $error->code = "2005";
        print_r(json_encode($error));
        die();

}
} else {

        $error = new stdClass();
        $error->message = "Not a valid Token!";
        $error->code = "2006";
        print_r(json_encode($error));
        die();
}

?>