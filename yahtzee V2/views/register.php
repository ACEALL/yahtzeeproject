<?php 
$page_title = 'Registraton';
require_once('../DbModel.php');
require_once('../Utilities.php');
require_once('../controllers/users.controller.php');

$db = new DbModel();
$util = new Utilities();
$controller = new UserController();

if (!$_SESSION["logged_in"]) {
    $util->redirect_user('login.php');
}
include('./headers/indexHeader.php');
if (isset($_SESSION['message'])) {
    $msg = $_SESSION['message'];
    unset($_SESSION['message']);
}
if(isset($msg)){ 
    if(is_array($msg))
    foreach($msg as $m) {
    echo $m;    }
    else echo $msg;
}
?>
<h1>Register</h1>
<form action="register.php" method="post">
	<p>First Name: <input type="text" name="first_name" size="15" maxlength="20" value="<?php if (isset($_POST['first_name'])) echo $_POST['first_name']; ?>" /></p>
	<p>Last Name: <input type="text" name="last_name" size="15" maxlength="40" value="<?php if (isset($_POST['last_name'])) echo $_POST['last_name']; ?>" /></p>
	<p>Email Address: <input type="text" name="email" size="20" maxlength="60" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>"  /> </p>
	<p>Password: <input type="password" name="pass_1" size="10" maxlength="20" value="<?php if (isset($_POST['pass_1'])) echo $_POST['pass_1']; ?>"  /></p>
	<p>Confirm Password: <input type="password" name="pass_2" size="10" maxlength="20" value="<?php if (isset($_POST['pass_2'])) echo $_POST['pass_2']; ?>"  /></p>
	<input type="hidden" name="type" value="Register"/>
	<p><input type="submit" name="submit" value="Register" /></p>
</form>
<?php
include('./footers/footer.html');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->handleRequest();
}?>
