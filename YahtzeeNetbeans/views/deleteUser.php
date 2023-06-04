<?php
require_once('../controllers/users.controller.php');
$controller = new UserController();
if (!$_SESSION["logged_in"]) {
    $util->redirect_user('login.php');
}
if ($_SESSION["level"] != 2) {
    $util->sendHome();
}
$page_title = 'Delete a User';
include('./headers/adminHeader.html');
echo '<h1>Delete a User</h1>';
$id = $controller->getUserId();
if ($id === null) {
    echo '<p class="error">This page has been accessed in error.</p>';
    include('./footers/footer.html');
    exit();
}

$user = $controller->getUser($id);
if ($user) {
    echo "<h3>Name: $user[0], $user[1]</h3>";
    echo 'Are you sure you want to delete this user?';
    echo '<form action="deleteUser.php" method="post">
        <input type="radio" name="sure" value="Yes" /> Yes 
        <input type="radio" name="sure" value="No" checked="checked" /> No
        <input type="submit" name="submit" value="Submit" />
        <input type="hidden" name="id" value="' . $id . '" />
		<input type="hidden" name="type" value="deleteUser"/>
    </form>';
} else {
    echo '<p class="error">This page has been accessed in error.</p>';
}

include('./footers/footer.html');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->handleRequest();
}
?>


?>