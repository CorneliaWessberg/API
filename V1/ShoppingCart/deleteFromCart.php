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

    if(isset($_GET['productId']) & isset($_GET['userId'])) {

    $cart->deleteFromCart($_GET['productId'], $_GET['userId']);
    $return = new stdClass();
    $return->message = "Product succesfully deleted from your shoppingcart!";
    print_r(json_encode($return));

}else {

        $error = new stdClass();
        $error->message = "Please choose a product id that you wanna delete!";
        $error->code = "2006";
        print_r(json_encode($error));
        die();

}
} else {

        $error = new stdClass();
        $error->message = "Not a valid Token, please login again!";
        $error->code = "2005";
        print_r(json_encode($error));
        die();
}

?>