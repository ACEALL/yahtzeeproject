<?php
require_once('config.php');
class DbModel{
    public function __construct(){
        $this->dbc = @mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $this->dbc = @mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );
        mysqli_set_charset($this->dbc, 'utf8');
    }

    public function returnUserCount(){
        $query = 'SELECT COUNT(user_id) FROM users';
        $run = @mysqli_query ($this->dbc, $query);
        $row = @mysqli_fetch_array ($run, MYSQLI_NUM);
        $records = $row[0];
        mysqli_free_result ($run);
        return $records;
    }
    
    public function returnListofUsers($order_by, $start, $display){
        $rows = [];
        $query = "SELECT last_name, first_name, DATE_FORMAT(registration_date, '%M %d, %Y') AS dr, user_id FROM users ORDER BY $order_by LIMIT $start, $display";		
        $run = @mysqli_query ($this->dbc, $query); 
        while ($row = mysqli_fetch_array($run, MYSQLI_ASSOC)) {
            array_push($rows,$row);
        }
        mysqli_free_result ($run);
        return $rows;
    }

    public function insertUsers($firstName,$lastName,$email,$password){
        $fn = mysqli_real_escape_string($this->dbc, trim($firstName));
        $ln = mysqli_real_escape_string($this->dbc, trim($lastName));
        $ema = mysqli_real_escape_string($this->dbc, trim($email));
        $pass = mysqli_real_escape_string($this->dbc, trim($password));
        $query = "INSERT INTO users (first_name, last_name, email, pass, permission_level, registration_date) VALUES ('$fn', '$ln', '$ema', SHA1('$pass'), 1,  NOW() )";		
		$run = @mysqli_query ($this->dbc, $query); 
        return $run ? $run : mysqli_error($this->dbc);
    }

    public function updateUsers($ids, $firstName,$lastName,$email,$permission){
        $fn = mysqli_real_escape_string($this->dbc, trim($firstName));
        $ln = mysqli_real_escape_string($this->dbc, trim($lastName));
        $ema = mysqli_real_escape_string($this->dbc, trim($email)); 
        $id = mysqli_real_escape_string($this->dbc, $ids);
        $permission_level = mysqli_real_escape_string($this->dbc, trim($permission));   	
        $query = "SELECT user_id FROM users WHERE first_name='$fn' AND user_id != $id";
		$run = @mysqli_query($this->dbc, $query);
		if (mysqli_num_rows($run) == 0) {
			$query = "UPDATE users SET first_name='$fn', last_name ='$ln', email='$ema', permission_level = '$permission_level' WHERE user_id=$id LIMIT 1";
			$run = @mysqli_query ($this->dbc, $query);
			return mysqli_affected_rows($this->dbc);
    }
    }

    public function loginValidate($email, $password){
        $ema = mysqli_real_escape_string($this->dbc, trim($email));
        $pass = mysqli_real_escape_string($this->dbc, trim($password));
        $query = "SELECT user_id, first_name, permission_level FROM users WHERE email='$ema' AND pass=SHA1('$pass')";		
		$run = @mysqli_query ($this->dbc, $query);
		if (mysqli_num_rows($run) == 1) {
			$row = mysqli_fetch_array ($run, MYSQLI_ASSOC);
			return array(true, $row);
        }else { 
        $errors[] = 'The email address and password entered do not match an existing user';
         } 
        return array(false, $errors);
    }
    public function updateUserPass($np, $row){
            $pass = mysqli_real_escape_string($this->dbc, trim($np));
            $query = "UPDATE users SET pass=SHA1('$pass') WHERE user_id=$row";		
			$run = @mysqli_query($this->dbc, $query);		
			if (mysqli_affected_rows($this->dbc) == 1) { 
                return true;
            }else{
                mysqli_error($this->dbc);
            }
    }
    public function returnUser($id){
        $query = "SELECT first_name, last_name, email, permission_level FROM users WHERE user_id=$id";	
        $run = @mysqli_query ($this->dbc, $query);   
        if (mysqli_num_rows($run) == 1) { 
	    $row = mysqli_fetch_array ($run, MYSQLI_NUM);
        }
        return isset($row)? $row : null;
    }

    public function deleteUser($id){
        $query = "DELETE FROM users WHERE user_id=$id LIMIT 1";		
        $run = @mysqli_query($this->dbc, $query);		
        if (mysqli_affected_rows($this->dbc) == 1) { 
            return true;
        }else{
            mysqli_error($this->dbc);
        }
    }

    
    public function returnAllItems(){
        $query = "SELECT itemName, category, itemDesc, price, onHand FROM items";		
        $run = @mysqli_query ($this->dbc, $query);
        $items = array();
        while ($row = mysqli_fetch_array($run, MYSQLI_ASSOC)) {
            $item = array(
		        'itemName' =>$row['itemName'],
		        'category' => $row['category'],
                'itemDesc' => $row['itemDesc'],
		        'price' => $row['price'],
                'onHand' =>$row['onHand'],
            );
        array_push($items,$item);
        } 
        return $items;
    }

    public function loadItemMangement($order_by, $start, $display){
        $rows = [];
        $query = "SELECT itemName, category, itemDesc, price, onHand, DATE_FORMAT(add_date, '%M %d, %Y') AS dr, item_id FROM items ORDER BY $order_by LIMIT $start, $display";		
        $run = @mysqli_query ($this->dbc, $query); 
        while ($row = mysqli_fetch_array($run, MYSQLI_ASSOC)) {
            array_push($rows,$row);
        }
        mysqli_free_result ($run);
        return $rows;
    }

    public function returnItem($id){
        $query = "SELECT itemName,  category, itemDesc, price FROM items WHERE item_id=$id";		
        $run = @mysqli_query ($this->dbc, $query);   
        if (mysqli_num_rows($run) == 1) { 
	    $row = mysqli_fetch_array ($run, MYSQLI_NUM);
        }
        return isset($row)? $row : null;
    }
    public function returnItemCount(){
        $query = 'SELECT COUNT(item_id) FROM items';
        $run = @mysqli_query ($this->dbc, $query);
        $row = @mysqli_fetch_array ($run, MYSQLI_NUM);
        $records = $row[0];
        mysqli_free_result ($run);
        return $records;
    }


    public function addItem($n,$cat,$desc,$p,$onH){
        $name = mysqli_real_escape_string($this->dbc, $n);
        $category = mysqli_real_escape_string($this->dbc, $cat);
        $descItem = mysqli_real_escape_string($this->dbc, $desc);
        $price = mysqli_real_escape_string($this->dbc, $p);
        $onHand = mysqli_real_escape_string($this->dbc, $onH);

        $query = "INSERT INTO items ( itemName, category, itemDesc , price , onHand, add_Date) VALUES ('$name', '$category', '$descItem', '$price', '$onHand',  NOW() )";		
		$run = @mysqli_query ($this->dbc, $query);
        return $run;
    }
    public function updateItem($iN, $ids, $ca, $de, $p){
        $itemName = mysqli_real_escape_string($this->dbc, $iN);
        $id = mysqli_real_escape_string($this->dbc, $ids);
        $des = mysqli_real_escape_string($this->dbc, $de);
        $price = mysqli_real_escape_string($this->dbc, $p);
        $cat = mysqli_real_escape_string($this->dbc, $ca);
        $query = "SELECT item_id FROM items WHERE itemName='$itemName' AND item_id != $id";
		$run = @mysqli_query($this->dbc, $query);
		if (mysqli_num_rows($run) == 0) {
			$query = "UPDATE items SET itemName='$itemName', category ='$cat', itemDesc='$des', price = '$price' WHERE item_id=$id LIMIT 1";
			$run = @mysqli_query ($this->dbc, $query);
			return mysqli_affected_rows($this->dbc);
    }
}
    public function deleteItem($id){
        $query = "DELETE FROM items WHERE item_id=$id LIMIT 1";		
		$run = @mysqli_query ($this->dbc, $query);
		return mysqli_affected_rows($this->dbc); 
    }
    public function closeDB(){
        mysqli_close($this->dbc); 
    }


 
}