<?php
session_start();
require_once('../Utilities.php');
require_once('../DbModel.php');

class gameController {
    private $db;
    private $util;
    public $maxPlayers = 4;
    public $numPlayers = 0;
    public function __construct() {
        $this->db = new DbModel();
        $this->util = new Utilities();
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['type'])) {
                switch ($_POST['type']) {
                    case 'start':
                        $this->startGame();
                        break;
                    case 'setup':
                        $this->gameSetup();
                        break;
                }
            }
        }
    }

    private function gameSetup(){
        $firstPlayerName = $_SESSION['name'] .'*' . $_SESSION['userid'];
        $maxPlayers = 4;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $numPlayers = $_POST['num_players'] ?? 0;
            $numPlayers = min(max(1, $numPlayers), $maxPlayers);
            $gameData = [
                'num_players' => $numPlayers,
                'players' => [],
            ];

            for ($i = 2; $i <= $numPlayers; $i++) {
                $playerName = $_POST['player_' . $i];
                $gameData['players'][] = $playerName;
            }

            $gameData['players'][] = $firstPlayerName;
            $gameDataJson = json_encode($gameData);
            setcookie('game_data', $gameDataJson, time() + (86400 * 30), '/');
        } else {
            $numPlayers = 2;
        }
        $this->util->redirect_user('../views/game.view.php');
    }

private function startGame(){

}
}
?>