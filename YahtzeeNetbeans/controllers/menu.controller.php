<?php
session_start();
require_once('../Utilities.php');
require_once('../DbModel.php');

class menuController {
    private $db;
    private $util;
    private $data;
    public function __construct() {
        $this->db = new DbModel();
        $this->util = new Utilities();
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['type'])) {
                switch ($_POST['type']) {
                    case 'main':
                        $this->main();
                        break;
                    case 'play':
                        $this->playGame();
                        break;
                    case 'rules':
                        $this->showRules();
                        break;
                }
            }
        }
        else{
            $this->main();
        }
    }

    private function main(){
        $this->data = '
        <div class = menu">
            <p>Please choose one of the following options:</p>
            <ul class="options-list">
                <form method="POST" action="index.php">
                    <input type="hidden" name="type" value="play"/>
                    <button type="submit">Play Game</button>
                </form>
                <form method="POST" action="index.php">
                    <input type="hidden" name="type" value="rules"/>
                    <button type="submit">Rules</button>
                </form> 
</ul>
        </div>
        ';
        
} 

    private function showRules(){
        $this->data = '
        <table>
		<tr>
			<th colspan="2">Instructions</th>
		</tr>
		<tr>
			<td colspan="2">Contents: 5 dice, score card</td>
		</tr>
	</table>
	<br>
	<table>
		<tr>
			<th>Object:</th>
		</tr>
		<tr>
			<td>Roll dice for scoring combinations, and get the highest total score.</td>
		</tr>
	</table>
	<br>
	<table>
		<tr>
			<th>Game Summary:</th>
		</tr>
		<tr>
			<td>On each turn, roll the dice up to 3 times to get the highest scoring combination for one of the 13 categories. After you finish rolling, you must place a score or a zero in one of the 13 category boxes on your score card. The game ends when all players have filled in their 13 boxes. Scores are totaled, including any bonus points. The player with the highest total wins.</td>
		</tr>
	</table>
	<br>
	<table>
		<tr>
			<th>How to Play:</th>
		</tr>
		<tr>
			<td>Each player takes a score card. The player to go first is randomized. This simulates each player randomly rolling for the highest total.</td>
		</tr>
	</table>
	<br>
	<table>
		<tr>
			<th>Taking A Turn:</th>
		</tr>
		<tr>
			<td>On your turn, you may roll the dice up to 3 times, although you may stop and score after your first or second roll. Dice will auto-roll on the start of each turn.</td>
		</tr>
		<tr>
			<td><strong>First Roll:</strong> Roll all 5 dice. Set any "keepers" aside. You may stop and score now, or roll again. To set any keepers aside, first enter the number of dice you would like to place on hold (0-5). Next, enter the dice number labeled Dice 1-5.</td>
		</tr>
		<tr>
			<td><strong>Second Roll:</strong> Reroll ANY or ALL dice you want - even "keepers" from the previous roll. You do not need to declare which combination you are rolling for; you may change your mind after any roll. You may stop and score after your second roll, or set aside any keepers and roll a third time. To set any keepers aside, first enter the number of dice you would like to place on hold (0-5). Next, enter the dice number labeled Dice 1-5.</td>
		</tr>
		<tr>
			<td><strong>Third and Final Roll:</strong> Reroll ANY or ALL dice you want. After your third roll, you MUST fill in a box on your score card with a score or a zero. After you fill a box, your turn is over.</td>
		</tr>
	</table>
	<br>
	<table>
		<tr>
        <th>Scoring:</th>
		</tr>
		<tr>
			<td>After each roll, you must choose a category to score in. You can only score each category once. The categories include:</td>
		</tr>
		<tr>
			<td>
				<ul>
					<li>Ones: Total of all dice showing the number 1</li>
					<li>Twos: Total of all dice showing the number 2</li>
					<li>Threes: Total of all dice showing the number 3</li>
					<li>Fours: Total of all dice showing the number 4</li>
					<li>Fives: Total of all dice showing the number 5</li>
					<li>Sixes: Total of all dice showing the number 6</li>
					<li>Three of a Kind: Total of all dice when at least 3 are the same</li>
					<li>Four of a Kind: Total of all dice when at least 4 are the same</li>
					<li>Full House: Score 25 points for a full house (3 of one number and 2 of another)</li>
					<li>Small Straight: Score 30 points for a small straight (sequence of 4 dice)</li>
					<li>Large Straight: Score 40 points for a large straight (sequence of 5 dice)</li>
					<li>Chance: Score the total of any combination of dice</li>
					<li>Yahtzee: Score 50 points for a yahtzee (all 5 dice the same)</li>
				</ul>
			</td>
		</tr>
	</table>
	<br>
	<table>
		<tr>
			<th>Scoring Combinations:</th>
		</tr>
		<tr>
			<td>In order to score in a specific category, you must meet the requirements for that category. For example, to score in the "Three of a Kind" category, you need at least 3 dice showing the same number. To score in the "Yahtzee" category, you need all 5 dice to show the same number. Each category has its own scoring rules.</td>
		</tr>
	</table>
	<br>
	<table>
		<tr>
			<th>Bonus:</th>
		</tr>
		<tr>
			<td>If the total of all categories (excluding the bonus) is 63 or higher, you will receive a bonus of 35 points. The bonus is added to your total score at the end of the game.</td>
		</tr>
	</table>
	<br>
	<table>
		<tr>
			<th>End of the Game:</th>
		</tr>
		<tr>
			<td>Once all players have filled in their 13 boxes on the score card, the game ends. Each player adds up their total score, including any bonus points. The player with the highest total score wins the game.</td>
		</tr>
	</table> 
    <form method="POST" action="index.php">
        <input type="hidden" name="type" value="main"/>
        <button type="submit">Go Back</button>
    </form> 
        ';
    }

    private function playGame(){
		if(!isset($_SESSION["logged_in"])){
			$this->util->redirect_user('../views/login.php');
		}
		elseif (!$_SESSION["logged_in"]) {
			$this->util->redirect_user('../views/login.php');
		}
		else{
			$this->util->redirect_user('../views/gameSetup.views.php');
		}
    }

    public function showData(){
        return $this->data;
    }

}
?>