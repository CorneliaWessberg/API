<?php 
include("../../config/databaseConnection.php");
include("../../objects/Users.php");


$user = new User($pdo); 
$user->deleteUser($_GET['id']);


?>