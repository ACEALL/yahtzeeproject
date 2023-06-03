<?php
require_once('../controllers/game.controller.php');
$controller = new gameController();
if(!$_SESSION["logged_in"] ) $util->redirect_user('login.php');
$page_title = 'GameSetup';
include('./headers/indexHeader.php');

?>
<h1> Welcome to the game of Yathzee!</h1>
  <p> </p>

    
      <div class="square-container">
        <div class="rounded-square">      
          <div class="table-container">
            <table>
              <tr>
              <th colspan="2">Upper Section</th>
              </tr>
              <tr>
              <th>Ones</th>
              <td class="score-cell"></td>
              </tr>
              <tr>
              <th>Twos</th>
              <td class="score-cell"></td>
              </tr>
              <tr>
              <th>Threes</th>
              <td class="score-cell"></td>
              </tr>
              <tr>
              <th>Fours</th>
              <td class="score-cell"></td>
              </tr>
              <tr>
              <th>Fives</th>
              <td class="score-cell"></td>
              </tr>
              <tr>
              <th>Sixes</th>
              <td class="score-cell"></td>
              </tr>
              <tr>
              <th>Upper Section Total</th>
              <td id="upperTotal"></td>
              </tr>
              <tr>
              <th colspan="2">Lower Section</th>
              </tr>
              <tr>
              <td>Three-Of-A-Kind</td>
              <td class="score-cell"></td>
              </tr>
              <tr>
              <td>Four-Of-A-Kind</td>
              <td class="score-cell"></td>
              </tr>
              <tr>
              <td>Full House</td>
              <td class="score-cell"></td>
              </tr>
              <tr>
              <td>Small Straight</td>
              <td class="score-cell"></td>
              </tr>
              <tr>
              <td>Large Straight</td>
              <td class="score-cell"></td>
              </tr>
              <tr>
              <td>Yahtzee</td>
              <td class="score-cell"></td>
              </tr>
              <tr>
              <td>Chance</td>
              <td class="score-cell"></td>
              </tr>
              <tr>
              <th>Lower Section Total</th>
              <td id="lowerTotal"></td>
              </tr>
              <tr>
              <th>Grand Total</th>
              <td id="grandTotal"></td>
              </tr>
            </table>
            <button id="zero">take Zero</button>
          </div>
        </div>
    
      </div>
    
      
      <div class="dice-section">
        <div id="dice">
        <div class="die" id="die1"><img src="/yahtzee/images/dice1.png" alt="Die 1"></div>
        <div class="die" id="die2"><img src="/yahtzee/images/dice1.png" alt="Die 2"></div>
        <div class="die" id="die3"><img src="/yahtzee/images/dice1.png" alt="Die 3"></div>
        <div class="die" id="die4"><img src="/yahtzee/images/dice1.png" alt="Die 4"></div>
        <div class="die" id="die5"><img src="/yahtzee/images/dice1.png" alt="Die 5"></div>
        </div>
        <button id="rollButton">Roll Dice</button>
      </div>
      
      <div class="center"><button id="startTurnButton" class="buttons">Start Turn</button></div>
      <div class="center"><button id="endTurnButton" class="buttons">End Turn</button></div>
      <form id="myForm" action="game.view.php" method="post">
     <input type="hidden" name="type" value="end">
        <button type="submit">End Game</button>
</form>
<?php
include ('./footers/footer.html'); 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->handleRequest();
}?>     
<script src="/yahtzee/js/script.js"></script>  