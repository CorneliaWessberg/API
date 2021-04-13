<?php 
include("../../config/databaseConnection.php");
include("../../objects/Users.php");


if(isset($_GET['username']) && ($_GET['user_email']) && ($_GET['password'])) {

    $username = $_GET['username'];
    $user_email = $_GET['user_email'];
    $password = $_GET['password']; 

}else {
    $error = new stdClass(); 
    $error->message = "Couldnt register, please try again!";
    $error->code = "0001";
    print_r(json_encode($error));
    die();

}

$user = new User($pdo);
$user->newUser($username, $user_email, $password);


?>