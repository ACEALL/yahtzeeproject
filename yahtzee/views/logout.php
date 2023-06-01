<?php 
require_once('../controllers/login.controller.php');
$itemController = new loginController();
$page_title = 'Logged Out!';
include ('./headers/indexHeader.php');
if($controller->logout()){
echo "<h1>Logged Out!</h1>
<p>You are now logged out!</p>";
}
else{
    echo '<p> Error occured</p>'
}
include ('../footers/footer.html');
?>