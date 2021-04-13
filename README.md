Hello and welcome to Cornelias webshop-endpoints! 

To start with first of all write the sql question down below in your database. 

This API uses the method GET, how to test the endpoints: follow every step here and put the information in the URL.

1. To start with go to: http://localhost/API_project_cornelia/V1/.
2. To begin with you need to register an user, if you wanna do that you just go to Users/addUser.php and put in your username, email, and password!
3. If you wanna log in you go to Users/loginUser.php and just put in your username and password!
You will automatically get logged out after one hour if you are not active on the site. 
4. When logged in you can create a new product at Products/addProduct.php, put in the values: title, description, image, price and category! 
5. if you wanna se all products just go to Products/allproducts.php and have a look. You can also se one specifik product at Products/specifikProduct.php or products by category at Products/showCategory.php. And you can edit a product at Products/editProduct.php.
6. if you wanna ad any product to your shoppingcart go to ShoppingCart/addToCart.php. 
7. at anytime you can delete the product from the cart just go to ShoppingCart/deleteFromCart.php, or if you wanna purchase the product you can go to ShoppingCart/checkOut.php. 

---------------------------------------------------------------------------------------------------------
ERROR CODES: 

Users starts with : 000
Products starts with : 100
Shoppingcart starts with: 200

---------------------------------------------------------------------------------------------------------
SQL question: 

DROP DATABASE IF EXISTS webshop;
CREATE DATABASE webshop; 

DROP TABLE IF EXISTS sessions; 
DROP TABLE IF EXISTS shoppingcart;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS users; 

CREATE TABLE users ( id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, username VARCHAR(40) NOT NULL, email VARCHAR(100) NOT NULL, password VARCHAR(150) NOT NULL, role VARCHAR(5) DEFAULT 'user') 
ENGINE = InnoDB;

CREATE TABLE products ( id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, title VARCHAR(40) NOT NULL, description VARCHAR(150) NOT NULL, image VARCHAR(100), price INT NOT NULL, category VARCHAR(60) NOT NULL) 
ENGINE = InnoDB;

CREATE TABLE shoppingcart ( id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, userId INT NOT NULL, productId INT NOT NULL, date DATETIME NOT NULL, 
CONSTRAINT FK_productId FOREIGN KEY(productId) REFERENCES products(id)
CONSTRAINT FK_userId FOREIGN KEY(userId) REFERENCES users(id)) 
ENGINE = innoDB;

CREATE TABLE sessions (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, user_id INT NOT NULL, token INT NOT NULL, last_used INT NOT NULL
CONSTRAINT FK_user_id FOREIGN KEY(user_id) REFERENCES users(id))
ENGINE = innoDB; 
