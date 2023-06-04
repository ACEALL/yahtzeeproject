<?php
session_start();
require_once('../Utilities.php');
require_once('../DbModel.php');

class loginController {
    private $db;
    private $util;

    public function __construct() {
        $this->db = new DbModel();
        $this->util = new Utilities();
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['type'])) {
                switch ($_POST['type']) {
                    case 'login':
                        $this->login();
                        break;
                    case 'logout':
                        $this->logout();
                        break;
                    case 'deleteUser':
                        $this->deleteUser();
                        break;
                }
            }
        }
    }

    private function login(){
        list ($check, $data) = $this->check_login( $_POST['email'], $_POST['pass']);
	if ($check) {
		$_SESSION["logged_in"]=true;
		$_SESSION["userid"]=$data["user_id"];
        $_SESSION['email'] = $_POST['email'];
		$_SESSION["name"]=$data["first_name"];
		$_SESSION["level"]=$data["permission_level"];
		if($_SESSION["level"] == 2){
			$this->util->redirect_user('../views/indexAdmin.php');	
		}
        else{
		$this->util->redirect_user('../views/gameSetup.views.php');	}
	} else { 
		$_SESSION["logged_in"]=false;
		$errors = $data;
	} 
} 


private function check_login( $email = '', $pass = '') {
	$errors = array(); 
	if (empty($email)) {
		$errors[] = 'You forgot to enter your email address.';
	} else {
		$email =trim($email);
	}
	if (empty($pass)) {
		$errors[] = 'You forgot to enter your password.';
	} else {
		$password = trim($pass);
	}
	if (empty($errors)) { // If everything's OK.
		$login = $this->db->loginValidate($email,$pass);
		return $login;
    }
}

    public function logout(){
        $_SESSION["logged_in"]=false;
        unset($_SESSION["userid"]);
        unset($_SESSION["name"]);
        unset($_SESSION["level"]);
    }

}
?>