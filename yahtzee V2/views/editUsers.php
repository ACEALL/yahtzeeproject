
<?php 
require_once('../controllers/users.controller.php');
$controller = new UserController();
if (!$_SESSION["logged_in"]) {
    $util->redirect_user('login.php');
}
if ($_SESSION["level"] != 2) {
    $util->sendHome();
}
$page_title = 'Edit a User';
include('./headers/adminHeader.html');
echo '<h1>Edit a User</h1>';
$id = $controller->getUserId();
if ($id === null) {
    echo '<p class="error">This page has been accessed in error.</p>';
    include ('./footers/footer.html');
    exit();
}

$user = $controller->getUser($id);
if ($user) {
    echo '<form action="editUsers.php" method="post">
        <p>First Name: <input type="text" name="first_name" size="15" maxlength="15" value="' . $user[0] . '" /></p>
        <p>Last Name: <input type="text" name="last_name" size="15" maxlength="30" value="' . $user[1] . '" /></p>
        <p>Email Address: <input type="text" name="email" size="20" maxlength="60" value="' . $user[2] . '"  /> </p>
        <p>Permission Level (1 is Standard, 2 is Admin): <input type="text" name="permission" size="1" maxlength="1" value="' . $user[3] . '"  /> </p>
        <p><input type="submit" name="submit" value="Submit" /></p>
        <input type="hidden" name="id" value="' . $id . '" />
        <input type="hidden" name="type" value="editUser"/>
    </form>';
} else {
    echo '<p class="error">This page has been accessed in error.</p>';
}

include ('./footers/footer.html');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->handleRequest();
}
?>
