<?php
require ('../Utilities.php');
$util = new Utilities();
session_start();
if(!$_SESSION["logged_in"] ) $util->redirect_user('login.php');
if($_SESSION["level"] !=2) $util->sendHome();
$page_title = 'Welcome to the Admin Side';
include('./headers/adminHeader.html');
?>
<h1>Welcome to the Admin Side</h1>
<h2>Admin Options</h2>
<div>
<table align="center" cellspacing="0" cellpadding="5" width="75%">
<tr>
	<td align="left"><b><a href="showUsers.php">Mange Users</a></b></td>
</tr>


</div>
<?php
include('./footers/footer.html');


