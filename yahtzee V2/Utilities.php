<?php 
class Utilities{
function redirect_user ($page = '../index.php') {
	$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
	$url = rtrim($url, '/\\');
	$url .= '/' . $page;
	if (!empty($_GET)) {
        $query = http_build_query($_GET);
        $url .= '?' . $query;
    }
	header("Location: $url");
	exit(); 

} 
function sendHome () {
	$url = 'http://' . $_SERVER['HTTP_HOST'];
	$url = rtrim($url, '/\\');
	$url .=  '/TestProject/index.php';
	header("Location: $url");
	exit(); 

} 
}