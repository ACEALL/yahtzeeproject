<?php
require_once('../controllers/menu.controller.php');
$controller = new menuController();
$controller->handleRequest();
$page_title = 'Yahtzee';
include('./headers/indexHeader.php');
?>
<div id=menu-container class="menu-container">
<h1>Welcome to Yahtzee</h1>
<?php 
echo $controller->showData();
include ('./footers/footer.html'); ?>
