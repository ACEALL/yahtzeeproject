<?php
require_once('../controllers/game.controller.php');
$controller = new gameController();
if(!$_SESSION["logged_in"] ) $util->redirect_user('login.php');
$page_title = 'GameSetup';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dynamic Form Example</title>
    <script>
        function updatePlayerFields() {
            var numPlayers = document.getElementById('num_players').value;
            var playerContainer = document.getElementById('player_container');

            // Clear previous player fields
            playerContainer.innerHTML = '';

            // Generate player fields dynamically
            for (var i = 2; i <= numPlayers; i++) {
                var label = document.createElement('label');
                label.setAttribute('for', 'player_' + i);
                label.textContent = 'Player ' + i + ' Name:';

                var input = document.createElement('input');
                input.setAttribute('type', 'text');
                input.setAttribute('id', 'player_' + i);
                input.setAttribute('name', 'player_' + i);

                playerContainer.appendChild(label);
                playerContainer.appendChild(input);
                playerContainer.appendChild(document.createElement('br'));
            }
        }
    </script>
</head>
<body>
    <h2>Game Setup</h2>
    <form method="POST">
    <p>Do you want to load the last save?</p>
        <label>
            <input type="radio" name="save" value="true">
            Yes
        </label>
        <br>
        <label>
            <input type="radio" name="save" value="false">
            No
        </label>
        <br>
        <label for="num_players">Number of Players (max <?php echo $controller->maxPlayers; ?>):</label>
        <select id="num_players" name="num_players" onchange="updatePlayerFields()">
            <?php
            for ($i = 1; $i <= $controller->maxPlayers; $i++) {
                $selected = ($i == $controller->numPlayers) ? 'selected' : '';
                echo "<option value=\"$i\" $selected>$i</option>";
            }
            ?>
        </select>
        <br>
        <div id="player_container">
            <?php
            for ($i = 2; $i <= $controller->numPlayers; $i++) {
                echo "<label for=\"player_$i\">Player $i Name:</label>";
                echo "<input type=\"text\" id=\"player_$i\" name=\"player_$i\"><br>";
            }
            ?>
        </div>
        <input type="hidden" name="type" value="setup"/>
        <input type="submit" value="Submit">
    </form>
<?php
include ('./footers/footer.html'); 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->handleRequest();
}?>