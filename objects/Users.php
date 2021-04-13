<?php 

/*Creates the class Users, where we create all the functions that involves users*/
Class User {

    private $database_connection;
    private $user_id; 
    private $username; 
    private $user_email; 
    private $password;


    function __construct($db) {
    $this->database_connection = $db; }

    
/*Creating a new function where we add a new User, 
Checks first that the user doesn't exist and then add the new User in our database 
*/
    function newUser($username_IN, $user_email_IN, $password_IN) {

        /*Check first if the query is valid, and if this user already exists so we wont get any double users */
         if(!empty($username_IN) && !empty($user_email_IN) && !empty($password_IN)) {

            $sql = "SELECT id FROM users WHERE username=:username_IN OR email=:email_IN";
            $stmt = $this->database_connection->prepare($sql);
            $stmt->bindParam(":username_IN", $username_IN);
            $stmt->bindParam(":user_email_IN", $user_email_IN);


            $countRows = $stmt->rowCount();
                if($countRows > 0) {
                    $error = new stdClass();
                    $error->message = "All arguments are required to create a user";
                    $error->code = "0001";
                    print_r(json_encode($error));
                    die();
                }

            /*Put the new values from the new user in the database*/
                $sql = "INSERT INTO users(username, email, password, role) VALUES(:username_IN, :email_IN, :password_IN, 'user')";
                $stmt = $this->database_connection->prepare($sql);
                $salt = "henfaoejb3422";
                $cryptpassword = md5($salt . $password_IN);
                $stmt->bindParam(":username_IN", $username_IN);
                $stmt->bindParam(":email_IN", $user_email_IN);
                $stmt->bindParam(":password_IN", $cryptpassword);
               

                $this->username = $username_IN;
                $this->user_email = $user_email_IN;

                if(!$stmt->execute()) {
                    $error = new stdClass();
                    $error->message = "All arguments are required to create a user";
                    $error->code = "0002";
                    print_r(json_encode($error));
                    die();
                }
                    $succeed = new stdClass(); 
                    $succeed->message = "User succesfully added! Username: $this->username Email: $this->user_email";
                    print_r(json_encode($succeed));
                

               
            }}
/*function where to login the user, checks so both username and password is correct otherwise error message will show*/ 
    function loginUser($username_IN, $password_IN) {
        $sql = "SELECT * FROM users WHERE username=:username_IN AND password=:password_IN";
        $stmt= $this->database_connection->prepare($sql);
        $salt = "henfaoejb3422";
        $cryptpassword = md5($salt . $password_IN);
        $stmt->bindParam(":username_IN",$username_IN);
        $stmt->bindParam(":password_IN",$cryptpassword);
        $stmt->execute();
        $countRows = $stmt->rowCount();
        
/*If user exist and type in right username and password we start a token/session*/
        if($countRows == 1) {
            $sucess = new stdClass();
            $sucess->message = "Succesfully logged in! Welcome $username_IN";
            print_r(json_encode($sucess));

            $row = $stmt->fetch();
            return $this->createToken($row['id'], $row['username']);

        }else {
            $error = new stdClass(); 
            $error->message = "Wrong username or password, please try again!";
            $error->code = "0003";
            print_r(json_encode($error));
            die();
        }

    }
/*function to create our token*/
    function createToken($id, $username) {

        $checkToken = $this->checkToken($id);

        if($checkToken != false) {
            return $checkToken;
        }

        $token = md5(time() . $id . $username);

        $sql = "INSERT INTO sessions(user_id, token, last_used) VALUES(:user_id_IN, :token_IN, :last_used_IN)";
        $stmt=$this->database_connection->prepare($sql);
        $stmt->bindParam(":user_id_IN", $id);
        $stmt->bindParam(":token_IN", $token);
        $time = time(); 
        $stmt->bindParam(":last_used_IN", $time);
        $stmt->execute(); 

        return $token;

    }

/*function that check our token*/
    function checkToken($id) {

        $sql = "SELECT token, last_used FROM sessions WHERE user_id=:userId_IN AND last_used > :active_time_IN LIMIT 1"; 
        $stmt=$this->database_connection->prepare($sql);
        $stmt->bindParam(":userId_IN", $id);
        $activeTime = time() - (60*60);
        $stmt->bindParam(":active_time_IN", $activeTime);
        $stmt->execute();

        $return = $stmt->fetch();

        if(isset($return['token'])) {
            return $return['token'];
        }else {
            return false;
        }



    }
/*function that checks that the token is valid*/
    function validToken($token) {
        
        $sql = "SELECT token, last_used FROM sessions WHERE token=:token_IN AND last_used > :active_time_IN LIMIT 1";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":token_IN", $token);
        $active_time = time() - (60*60);                                 
        $statement->bindParam(":active_time_IN", $active_time);

        $statement->execute();
        $return = $statement->fetch();

        if(isset($return['token'])) {
 
            $this->updateToken($return['token']);
            
            return true;

        } else {
            
            return false;

        }

    }
/*function that updates our token*/
    function updateToken($token) {
        
        $sql = "UPDATE sessions SET last_used=:last_used_IN WHERE token=:token_IN";
        $statement = $this->database_connection->prepare($sql);
        $time = time();
        $statement->bindParam(":last_used_IN", $time);
        $statement->bindParam(":token_IN", $token);
        $statement->execute();

    }
/*function where we can delete a user*/
    function deleteUser($user_id) {
        $sql = "DELETE FROM users WHERE id=:user_ID_IN"; 
        $stmt=$this->database_connection->prepare($sql);
        $stmt->bindParam(":user_ID_IN", $user_id);
        $stmt->execute(); 

        if($stmt->rowCount() > 0) {
            print_r("User with id $user_id is deleted!");
        }else {
            print_r("Couldnt find user with $user_id");
        }


        }

    }
