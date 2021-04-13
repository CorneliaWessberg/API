<?php
include("../../config/databaseConnection.php");
include("../../objects/Users.php");

if(isset($_GET['username'])&&isset($_GET['password'])){
    $username = $_GET['username'];
    $password = $_GET['password'];
}

    $user = new User($pdo); 

    $sucess = new stdClass();
    $sucess->token = $user->loginUser($username, $password);
    print_r(json_encode($sucess)); 



?>