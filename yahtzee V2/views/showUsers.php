<?php 
require_once('../DbModel.php');
require_once('../Utilities.php');
require_once('../controllers/users.controller.php');

$db = new DbModel();
$util = new Utilities();
$controller = new UserController();

if (!$_SESSION["logged_in"]) {
    $util->redirect_user('login.php');
}
if ($_SESSION["level"] != 2) {
    $util->sendHome();
}

$page_title = 'Manage the Current Users';
include('./headers/adminHeader.html');
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
echo '<h1>Registered Users</h1>';

$display = 10;
if (isset($_GET['p']) && is_numeric($_GET['p'])) {
    $pages = $_GET['p'];
} else {
    $records = $controller->returnUserCount();
    if ($records > $display) {
        $pages = ceil($records / $display);
    } else {
        $pages = 1;
    }
}
if (isset($_GET['s']) && is_numeric($_GET['s'])) {
    $start = $_GET['s'];
} else {
    $start = 0;
}

$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'rd';

// Determine the sorting order:
switch ($sort) {
    case 'ln':
        $order_by = 'last_name ASC';
        break;
    case 'fn':
        $order_by = 'first_name ASC';
        break;
    case 'rd':
        $order_by = 'registration_date ASC';
        break;
    default:
        $order_by = 'registration_date ASC';
        $sort = 'rd';
        break;
}

echo '<table align="center" cellspacing="0" cellpadding="5" width="75%">
<tr>
    <td align="left"><b>Edit</b></td>
    <td align="left"><b>Delete</b></td>
    <td align="left"><b><a href="showUsers.php?sort=ln">Last Name</a></b></td>
    <td align="left"><b><a href="showUsers.php?sort=fn">First Name</a></b></td>
    <td align="left"><b><a href="showUsers.php?sort=rd">Date Registered</a></b></td>
</tr>';
$bg = '#eeeeee';
$rows = $controller->getListofUsers($order_by, $start, $display);
foreach ($rows as $row) {
    $bg = ($bg == '#eeeeee' ? '#ffffff' : '#eeeeee');
    echo '<tr bgcolor="' . $bg . '">
        <td align="left"><a href="editUsers.php?id=' . $row['user_id'] . '">Edit</a></td>
        <td align="left"><a href="deleteUser.php?id=' . $row['user_id'] . '">Delete</a></td>
        <td align="left">' . $row['last_name'] . '</td>
        <td align="left">' . $row['first_name'] . '</td>
        <td align="left">' . $row['dr'] . '</td>
    </tr>';
}

echo '</table>';








// require '../DbModel.php';
// $db = new DbModel();
// require ('../Utilities.php');
// $util = new Utilities();
// session_start();
// if(!$_SESSION["logged_in"] ) $util->redirect_user('login.php');
// if($_SESSION["level"] !=2) $util->sendHome();
// $page_title = 'Manage the Current Users';
// include ('./includes/header.html');
// echo '<h1>Registered Users</h1>';
// $display = 10;
// if (isset($_GET['p']) && is_numeric($_GET['p'])) { 
// 	$pages = $_GET['p'];
// } else { 
// 	$records = $db->returnUserCount();
// 	if ($records > $display) { 
// 		$pages = ceil ($records/$display);
// 	} else {
// 		$pages = 1;
// 	}
// } 
// if (isset($_GET['s']) && is_numeric($_GET['s'])) {
// 	$start = $_GET['s'];
// } else {
// 	$start = 0;
// }

// $sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'rd';

// // Determine the sorting order:
// switch ($sort) {
// 	case 'ln':
// 		$order_by = 'last_name ASC';
// 		break;
// 	case 'fn':
// 		$order_by = 'first_name ASC';
// 		break;
// 	case 'rd':
// 		$order_by = 'registration_date ASC';
// 		break;
// 	default:
// 		$order_by = 'registration_date ASC';
// 		$sort = 'rd';
// 		break;
// }

// echo '<table align="center" cellspacing="0" cellpadding="5" width="75%">
// <tr>
// 	<td align="left"><b>Edit</b></td>
// 	<td align="left"><b>Delete</b></td>
// 	<td align="left"><b><a href="showUsers.php?sort=ln">Last Name</a></b></td>
// 	<td align="left"><b><a href="showUsers.php?sort=fn">First Name</a></b></td>
// 	<td align="left"><b><a href="showUsers.php?sort=rd">Date Registered</a></b></td>
// </tr>
// ';
// $bg = '#eeeeee'; 
// $rows = $db->returnListofUsers($order_by, $start, $display);
// foreach($rows as $row) {
// 	$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee');
// 		echo '<tr bgcolor="' . $bg . '">
// 		<td align="left"><a href="editUsers.php?id=' . $row['user_id'] . '">Edit</a></td>
// 		<td align="left"><a href="deleteUser.php?id=' . $row['user_id'] . '">Delete</a></td>
// 		<td align="left">' . $row['last_name'] . '</td>
// 		<td align="left">' . $row['first_name'] . '</td>
// 		<td align="left">' . $row['dr'] . '</td>
// 	</tr>
// 	';
// } 

// echo '</table>';

// if ($pages > 1) {
	
// 	echo '<br /><p>';
// 	$current_page = ($start/$display) + 1;
	
// 	if ($current_page != 1) {
// 		echo '<a href="showUsers.php?s=' . ($start - $display) . '&p=' . $pages . '&sort=' . $sort . '">Previous</a> ';
// 	}
	
// 	for ($i = 1; $i <= $pages; $i++) {
// 		if ($i != $current_page) {
// 			echo '<a href="showUsers.php?s=' . (($display * ($i - 1))) . '&p=' . $pages . '&sort=' . $sort . '">' . $i . '</a> ';
// 		} else {
// 			echo $i . ' ';
// 		}
// 	}
// 	if ($current_page != $pages) {
// 		echo '<a href="showUsers.php?s=' . ($start + $display) . '&p=' . $pages . '&sort=' . $sort . '">Next</a>';
// 	}
	
// 	echo '</p>'; // 
	
// }
	
// include ('../include/footer.html');
// ?>