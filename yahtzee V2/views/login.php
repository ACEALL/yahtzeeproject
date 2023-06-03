<?php 
require_once('../controllers/login.controller.php');
$controller = new loginController();
$page_title = 'Login';
include ('./headers/indexHeader.php');
//include('./headers/adminHeader.html');
if (isset($_SESSION['message'])) {
    $msg = $_SESSION['message'];
    unset($_SESSION['message']);
}
?><h1>Login</h1>
<form action="login.php" method="post">
	<p>Email Address: <input type="text" name="email" size="20" maxlength="60" /> </p>
	<p>Password: <input type="password" name="pass" size="20" maxlength="20" /></p>
	<p><input type="submit" name="submit" value="Login" /></p>
    <input type="hidden" name="type" value="login"/>
</form>

<?php include ('./footers/footer.html');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->handleRequest();
} ?>
