<?php
/* Creating our class involving products, and here is all the functions that includes products*/
Class Products {

    private $database_connection;
    private $title; 
    private $description; 
    private $price; 
    private $image; 
    private $category;
    
    function __construct($db) {
        $this->database_connection = $db;
    }
    
        
/*Function for create a new product with all it's values, checks first the product doesn't exist already*/
    function newProduct($title_IN, $description_IN, $image_IN, $price_IN, $category_IN) {
        if(!empty($title_IN) && !empty($description_IN) && !empty($image_IN) && !empty($price_IN) && !empty($category_IN)) {

        $sql = "SELECT  name,description,image, price, category FROM products WHERE name = :title_IN AND description = :description_IN AND image = :image_IN AND category = :category_IN AND price = :price_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":title_IN", $title_IN);
        $stmt->bindParam(":description_IN", $description_IN);
        $stmt->bindParam(":image_IN", $image_IN);
        $stmt->bindParam(":price_IN", $price_IN);
        $stmt->bindParam(":category_IN", $category_IN);
        $stmt->execute();
       

        $countRows = $stmt->rowCount();
            if($countRows > 0) {
                $error = new stdClass();
                $error->text =  "This product aldready exists!";
                $error->code = "2001";
                print_r(json_encode($error));
                die();

            }
        }
/*Putting the new values in the database*/
        $sql = "INSERT INTO products(title, description, image, price, category) VALUES(:title_IN, :description_IN, :image_IN, :price_IN, :category_IN)";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":title_IN", $title_IN);
        $stmt->bindParam(":description_IN", $description_IN);
        $stmt->bindParam(":image_IN", $image_IN);
        $stmt->bindParam(":price_IN", $price_IN);
        $stmt->bindParam(":category_IN", $category_IN);
        $stmt->execute(); 
        $message = new stdClass();
        


     /*Checks that all arguments are filled in for create the product*/   
        if(!$stmt->execute()) {
            $error = new stdClass();
            $error->text = "all arguments needed to create a new product!";
            $error->code = "0007";
            print_r(json_encode($error)); 
            die();
        }

        $this->title = $title_IN;
        $this->price = $price_IN;
        $this->category = $category_IN;

        $message->text = "Product succesfully added! Title: $this->title , Price: $this->price , Category: $this->category";
        print_r(json_encode($message));
    }
/*function where we delete our product*/
    function deleteProduct($productId) {


        $sql = "DELETE FROM products WHERE id=:productId_IN"; 
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":productId_IN", $productId);
        $stmt->execute();
        $message = new stdClass();

        if($stmt->rowCount() > 0) {
            $message->message = "Product with $productId is deleted!";
            print_r(json_encode($message));
        }else {
            $message->message = "No product with id: $productId was found.";
            print_r(json_encode($message));
        }


    }

/*function to update our product, you dont have to update all fields at once, you can do one if you only wanna change one value*/
    function updateProduct($id, $title ="", $description ="", $image="", $price="", $category ="") {


        $error = new stdClass();
        if(!empty($title)) {
            $error->message = $this->updateTitle($id, $title);
        }

        if(!empty($description)) {
            $error->message = $this->updateDescription($id, $description);
        }

        if(!empty($image)) {
            $error->message = $this->updatePrice($id, $price); 
        }

        if(!empty($price)) {
            $error->message = $this->updateImage($id, $image);
        }

        if(!empty($category)) {
            $error->message = $this->updateCategory($id, $category);
        }

        die();

    }

    function updateTitle($id, $title) {

        $sql = "UPDATE products SET title = :title_IN WHERE id = :id_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":id_IN", $id);
        $stmt->bindParam(":title_IN", $title); 
        $stmt->execute(); 
        $message = new stdClass();
        $message->message = "Title for product with id: $id was succesfully updated!";

        if($stmt->rowCount() < 1) {
            $message->message = "No product with id:$id was found, try agan.";
            print_r(json_encode($message));
        }
        
        print_r(json_encode($message)); 
        
        
    }

    function updateDescription($id, $description) {

        $sql = "UPDATE products SET description = :description_IN WHERE id = :id_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":id_IN", $id);
        $stmt->bindParam(":description_IN", $description); 
        $stmt->execute(); 
        $message = new stdClass();
        $message->message = "description for product with id: $id was succesfully updated!";

        if($stmt->rowCount() < 0) {
            $message->message ="No product with id:$id was found, try agan.";
            print_r(json_encode($message)); 
        }

        print_r(json_encode($message)); 
           
        

    }


    function updateImage($id, $image) {

        $sql = "UPDATE products SET image = :image_IN WHERE id = :id_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":id_IN", $id);
        $stmt->bindParam(":image_IN", $image); 
        $stmt->execute(); 
        $message = new stdClass();
        $message->message = "Image for product with id: $id was succesfully updated!";

        if($stmt->rowCount() < 0) {
            $message->message ="No product with id:$id was found, try agan.";
            print_r(json_encode($message)); 
        }

        print_r(json_encode($message)); 
    }

    function updatePrice($id, $price) {

        $sql = "UPDATE products SET price = :price_IN WHERE id = :id_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":id_IN", $id);
        $stmt->bindParam(":price_IN", $price); 
        $stmt->execute(); 
        $message = new stdClass();
        $message->message = "Price for product with id: $id was succesfully updated!";

        if($stmt->rowCount() < 0) {
            $message->message ="No product with id:$id was found, try agan.";
            print_r(json_encode($message)); 
        }

        print_r(json_encode($message)); 
    }

    function updateCategory($id, $category) {

        $sql = "UPDATE products SET category = :category_IN WHERE id = :id_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":id_IN", $id);
        $stmt->bindParam(":category_IN", $category); 
        $stmt->execute(); 
        $message = new stdClass();
        $message->message = "Category for product with id: $id was succesfully updated!";

        if($stmt->rowCount() < 0) {
            $message->message ="No product with id:$id was found, try agan.";
            print_r(json_encode($message)); 
        }

        print_r(json_encode($message)); 
    }
/*function that list our products*/
    function showProducts() {

        $sql = "SELECT * FROM products"; 
        $stmt = $this->database_connection->prepare($sql);
        $stmt->execute(); 

        print_r(json_encode($stmt->fetchAll())); 
        
    }

/*function that shows a specifik product*/
    function specifikProduct($productId) {

        $sql = "SELECT title, description, price, image, category FROM products WHERE id = :id_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":id_IN", $productId);
        $stmt->execute();
        $row = $stmt->fetch();

        if($stmt->rowCount() > 0) {
            $this->title = $row['title']; 
            $this->description = $row['description'];
            $this->price = $row['price'];
            $this->image = $row['image']; 
            $this->category = $row['category'];

            
            print_r(json_encode($row));

        }else {
            $error = new stdClass();
            $error->message = "Couldn't find the id";
            $error->code = "2003";
            print_r(json_encode($error));
            die();
            }

    } 

/*function where we can show our products in their categorys*/
    function showCategory($category_IN) {

        $sql = "SELECT * FROM products WHERE category LIKE :category_IN"; 
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":category_IN", $category_IN);
        $stmt->execute();
        $countRows = $stmt->rowCount();

        if($countRows > 1) {
            $row = $stmt->fetchAll();
            print_r(json_encode($row));
        }else {
            $error = new stdClass();
            $error->message = "Couldnt find any products within this category";
            $error->code = "2004";
            print_r(json_encode($error));
            die();
        }

    }
}

?>

