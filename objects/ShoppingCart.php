<?php 
/*Creating our Class involving shoppingcart and write all the functions include the shoppingcart*/
class shoppingCart {

    private $database_connection; 
    private $userId;
    private $productId;
    private $date; 
    


    function __construct($db) {
    $this->database_connection = $db;
}

/*function where we add our product to the shoppingcart*/
    function addToCart($userId_IN, $productId_IN) {
        if(!empty($userId_IN) && !empty($productId_IN)) {

            $sql = "SELECT * FROM shoppingcart WHERE userId=:user_id_IN AND productId= product_id_IN";
            $stmt = $this->database_connection->prepare($sql);
            $stmt->bindParam(":user_id_IN", $userId_IN);
            $stmt->bindParam(":product_id_IN", $productId_IN);
            $stmt->execute();
            $row = $stmt->rowCount();

            $error = new stdClass();
            if($row > 0) {
                $error->message = "This product already exists in cart"; 
                $error->code = "3002";
                return json_encode($error);
                die();
            }

            $sql = "INSERT INTO shoppingcart(userId, productId) VALUES (:user_id_IN, :product_id_IN)";
            $stmt = $this->database_connection->prepare($sql);
            $stmt->bindParam(":user_id_IN", $userId_IN);
            $stmt->bindParam(":product_id_IN", $productId_IN);
            $stmt->execute();

            
            if(!$stmt->execute()) {
                $error = new stdClass();
                $error->message = "Couldnt add the product to Cart, please try again!";
                $error->code = "3003";
                return json_encode($error);
                die();
            }

        }

    }
/*function where we can delete the product from the shoppingcart*/
    function deleteFromCart($userId_IN, $productId_IN) {

        $sql = "SELECT * FROM shoppingcart WHERE userId = :user_id_IN AND productId = :product_id_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":user_id_IN", $userId_IN);
        $stmt->bindParam(":product_id_IN", $productId_IN);
        $stmt->execute();
        $row = $stmt->rowCount();

        if($row < 1) {
            $error = new stdClass();
            $error->message = "Product with id: $productId_IN doesn't exists in user's with id: $userId_IN cart!";
            $error->code = "3004";
            return json_encode($error);
            die();
        }

        $sql = "DELETE * FROM shoppingcart WHERE userId = :user_id_IN AND productId = :product_id_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":user_id_IN", $userId_IN);
        $stmt->bindParam(":product_id_IN", $productId_IN);
        $stmt->execute();

        if(!$stmt->execute()) {
            $error = new stdClass();
            $error->message = "Couldn't remove from shoppingcart, please try again!";
            $error->code = "3005";
            return json_encode($error);
            die();
        }

        $success = new stdClass();
        $success->message = "Product with id: $productId_IN from user with id: $userId_IN was succesfully deleted from shoppingcart!";
        return json_encode($success);

    }

/*function where we check out our cart*/
    function checkoutCart($token_IN) {
        $sql = "DELETE FROM shoppingcart WHERE token = :token_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":token_IN", $token_IN); 
        $stmt->execute(); 

        if(!$stmt->execute()) {
            $error = new stdClass();
            $error->message = "Couldn't process your purchase, please try again!";
            $error->code = "3006";
            return json_encode($error);
            die();
        }

        $success = new stdClass();
        $success->message = "Thank you for your purchase!";
        print_r(json_encode($success));
    }
}


?>