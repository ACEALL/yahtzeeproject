<?php
session_start();
require_once('../Utilities.php');
require_once('../DbModel.php');

class userController {
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
                    case 'Register':
                        $this->createUser();
                        break;
                    case 'editUser':
                        $this->updateUser();
                        break;
                    case 'deleteUser':
                        $this->deleteUser();
                        break;
                }
            }
        }
    }

    public function createUser(){
        $errors = array(); 
        if (empty($_POST['first_name'])) {
            $errors[] = 'You forgot to enter your first name.';
        } else {
            $firstName = trim($_POST['first_name']);
        }
        
        if (empty($_POST['last_name'])) {
            $errors[] = 'You forgot to enter your last name.';
        } else {
            $lastName = trim($_POST['last_name']);
        }
        if (empty($_POST['email'])) {
            $errors[] = 'You forgot to enter your email address.';
        } else {
            $email = trim($_POST['email']);
        }
        if (!empty($_POST['pass_1'])) {
            if ($_POST['pass_1'] != $_POST['pass_2']) {
                $errors[] = 'Your password did not match the confirmed password.';
            } else {
                $password =  trim($_POST['pass_1']);
            }
        } else {
            $errors[] = 'You forgot to enter your password.';
        }
        
        if (empty($errors)) { 
            // Register the user in the database
            $run = $this->db -> insertUsers($firstName,$lastName,$email,$password);
            if ($run) { 
                $_SESSION['message'] ='<p>You are now registered</p>';	
            
            } else {	
                $_SESSION['message'] = '<p class="error">You could not be registered due to a system error</p>'; 
			
            } 
   
            $this->util->redirect_user('register.php');
        } else {
            $_SESSION['message'] = $errors;

            //$this->db->closeDB();
            $this->util->redirect_user('register.php');
        }
    }

    public function updateUser() {
        $errors = [];

        if (empty($_POST['first_name'])) {
            $errors[] = 'You forgot to enter the first name.';
        } else {
            $firstName =  trim($_POST['first_name']);
        }

        if (empty($_POST['last_name'])) {
            $errors[] = 'You forgot to enter the last name.';
        } else {
            $lastName = trim($_POST['last_name']);
        }

        if (empty($_POST['email'])) {
            $errors[] = 'You forgot to enter the email.';
        } else {
            $email = trim($_POST['email']);
        }

        if (empty($_POST['permission'])) {
            $errors[] = 'You forgot to enter the permission Level.';
        } else {
            $permission_level = trim($_POST['permission']);
        }

        if (empty($_POST['id'])) {
            $errors[] = 'ID is missing.';
        } else {
            $id = trim($_POST['id']);
        }

        if (empty($errors)) {
            if ($this->db->updateUsers($id, $firstName, $lastName, $email, $permission_level) == 1){
                $_SESSION['message'] = '<p>The user has been edited.</p>';
            } else {
                $_SESSION['message'] = '<p class="error">The user could not be edited due to a system error. We apologize for any inconvenience.</p>';
            }

            //$this->db->closeDB();
            $this->util->redirect_user('showUsers.php');
        } else {
            $_SESSION['message'] = $errors;

            //$this->db->closeDB();
            $this->util->redirect_user('showUsers.php');
        }
    }

    public function deleteUser() {
        if (empty($_POST['id'])) {
            $errors[] = 'ID is missing.';
        } else {
            $id = trim($_POST['id']);
        }

        if (empty($errors)) {
            if($this->db->deleteUser($id)){
            $_SESSION['message'] = '<p>The user has been deleted.</p>';
        } else {
            $_SESSION['message'] = '<p class="error">The user could not be edited due to a system error. We apologize for any inconvenience.</p>';
        }
        }
        else{
            $_SESSION['message'] = $errors;

            //$this->db->closeDB();
            
        }
        $this->util->redirect_user('showUsers.php');
        }
    

    public function getUser($id){
        return $this->db->returnUser($id);

    }
    public function getListofUsers($order_by, $start, $display){
       return $this->db->returnListofUsers($order_by, $start, $display);
    }
    public function returnUserCount(){
        $this->db->returnUserCount();
    }

    public function getUserId() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']) && is_numeric($_GET['id'])) {
            return $_GET['id'];
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && is_numeric($_POST['id'])) {
            return $_POST['id'];
        } else {
            return null;
        }
    }
}
?>