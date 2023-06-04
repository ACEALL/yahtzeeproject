  class Game {
    constructor() {
      this.playerDone = [];
       var saveDataFromCookie = this.getSaveDataFromCookie();
      var gameData = this.getGameDataFromCookie();
      if (gameData) {
        this.numPlayers = gameData.num_players;
        var play= gameData.players;
        console.log("Number of Players: " + this.numPlayers);
        console.log("Player Names: " + play.join(", "));
      } else {
        console.log("Game data not found in the cookie.");
      }
        this.diceGame = new DiceGame();
        this.players = [];
        for(let i = 0; i < this.numPlayers; i++){
          if(i == this.numPlayers-1){
            let p = play[this.numPlayers-1].split('*');
            console.log("p =" + p);
            this.players.push(new Player(p[0], p[1],this.diceGame,saveDataFromCookie));
          }
            else this.players.push(new Player(play[i], 'g' + i,this.diceGame));
        }
        document.getElementById("rollButton").classList.toggle("hide");
      this.currentPlayerIndex = 0;
    }
  
    getCurrentPlayer() {
      return this.players[this.currentPlayerIndex];
    }
  
    nextTurn() {
      this.currentPlayerIndex = (this.currentPlayerIndex + 1) % this.players.length;
    }

    startTurn(){
       if(!this.playerIsDone()){
        document.getElementById("rollButton").classList.toggle("hide");
        this.players[this.currentPlayerIndex].takeTurn();
        document.getElementById("startTurnButton").classList.toggle("hide");
    }

    }

    playerIsDone(){
      if(this.players[this.currentPlayerIndex].pdone()){
        if(!this.playerDone.includes(this.currentPlayerIndex)) this.playerDone.push(this.currentPlayerIndex);
        if(this.numPlayers == this.playerDone.length) this.gameOver();
        document.getElementById("startTurnButton").classList.toggle("hide");
        document.getElementById("rollButton").classList.toggle("show");
        document.getElementById("endTurnButton").classList.toggle("hide");
        alert(this.players[this.currentPlayerIndex].getName() + " Has Finished")
        this.nextTurn();
      }
    }

    endTurn(){
      this.players[this.numPlayers-1].saveCard();
       if( this.players[this.currentPlayerIndex].turnDone()){
	      document.getElementById("startTurnButton").classList.toggle("hide");
        document.getElementById("rollButton").classList.toggle("hide");
        document.getElementById("endTurnButton").classList.toggle("hide");
        this.nextTurn();
       }
       else{
        alert("player is not finished");
       }
    }

    gameOver(){
      this.players[this.numPlayers-1].saveCard();
      alert(this.findWinner());
      document.getElementById("startTurnButton").classList.toggle("hide");
      document.getElementById("rollButton").classList.toggle("hide");
      document.getElementById("endTurnButton").classList.toggle("hide");
    }
    findWinner(){
      let winner = 0;
      let topScore = 0;
      for(let x in this.players){
        if(this.Player[x].getGrandTotal() > topScore ){
          topScore =  this.Player[x].getGrandTotal();
          winner = x;
        }
      }
      return 'The Winner is' + this.players[winner].getName() + ' with a score of ' + topScore;
    }
    getGameDataFromCookie() {
      var gameDataCookie = document.cookie
        .split(';')
        .map(cookie => cookie.trim())
        .find(cookie => cookie.startsWith('game_data='));
    
      if (gameDataCookie) {
        var gameDataJson = decodeURIComponent(gameDataCookie.substring(10));
        var gameData = JSON.parse(gameDataJson);
        return gameData;
      }
    
      return null;
    }
    getSaveDataFromCookie() {
      var saveDataCookie = document.cookie
        .split(';')
        .map(cookie => cookie.trim())
        .find(cookie => cookie.startsWith('save_data='));
      if (saveDataCookie) {
        var saveDataJson = decodeURIComponent(saveDataCookie.substring(10));
        var saveData = JSON.parse(saveDataJson);
        var data = JSON.stringify(saveData);
        return data;
      }
    
      return null;
    }
  }


class Player {
    constructor(username,userId,gameDice, card = null) {
      this.userId = userId;
      this.username = username;
      this.diceGame = gameDice;
      this.playerDone = false;
      if(card != null){
        this.scoreCard = card;
      }
      else{
      this.scoreCard = '[{"header0":"Upper Section"},{"header0":"Ones","header1":""},{"header0":"Twos","header1":""},{"header0":"Threes","header1":""},{"header0":"Fours","header1":""},{"header0":"Fives","header1":""},{"header0":"Sixes","header1":""},{"header0":"Upper Section Total","header1":""},{"header0":"Lower Section"},{"header0":"Three-Of-A-Kind","header1":""},{"header0":"Four-Of-A-Kind","header1":""},{"header0":"Full House","header1":""},{"header0":"Small Straight","header1":""},{"header0":"Large Straight","header1":""},{"header0":"Yahtzee","header1":""},{"header0":"Chance","header1":""},{"header0":"Lower Section Total","header1":""},{"header0":"Grand Total","header1":""}]';
    }}
    getName(){
      return this.username;
    }

    saveCard(){
      const json = this.scoreCard;
      const cookieName = 'gameCard';
      const cookieValue = encodeURIComponent(json);
    document.cookie = `${cookieName}=${cookieValue};  path=/`;
    }

    pdone(){
      return this.playerDone;
    }
    getGrandTotal() {
      const grandTotalObj = this.scoreCard.find(obj => obj.header0 === "Grand Total");
      const grandTotal = grandTotalObj.header1;   
      return grandTotal;
    }

    takeTurn(){
      alert(this.username + " Turn!")
        this.turnStatus = false;
         // Create a new dice game object for each player's turn
        this.diceGame.resetDice();
        this.turn = new Turn(this.scoreCard, this.diceGame);
    }
     turnDone() {
        if(this.turn.over()){
            this.scoreCard=this.turn.tableToJson();
            if(this.turn.done()) this.playerDone = true;
            delete this.turn;;
            this.diceGame.resetDice();
            return true;
        }
        else return false;
     }
  
    getScoreCard() {
      return this.scoreCard;
    }
  }
  

  
  class Turn {
    constructor(scoreCard, diceGame) {
      this.json = scoreCard;
      this.gameOver = false;
      this.skip = false;
      console.log(this.tableToJson());
      this.cells = document.querySelectorAll(".score-cell");
      this.zero = document.getElementById("zero");
      this.upperTotal = document.getElementById("upperTotal");
      this.lowerTotal = document.getElementById("lowerTotal");
      this.grandTotal = document.getElementById("grandTotal");
      this.stat = false;
      this.dice = diceGame;
      this.populateTableFromJson(this.json);
      this.cells.forEach((cell) => {
        cell.isFilled = false;
        cell.addEventListener("click", () => {
            const categoryName = cell.previousElementSibling.textContent;
            console.log("Clicked category:", categoryName);
          if (!cell.isFilled) {
            var score = 0;
            let rolledDice = this.dice.getSavedDice()
            if(this.skip){
              score = this.calculateScore(categoryName,0)
              cell.textContent = score;
              cell.isFilled = true;
              this.skip = false;
              console.log(this.tableToJson());
              this.stat = true;
	            document.getElementById("endTurnButton").classList.toggle("hide");
            }
            else if (this.checkCategory(categoryName,rolledDice)) {
              score = this.calculateScore(categoryName,rolledDice)
              cell.textContent = score;
              cell.isFilled = true;
              this.updateTotals();
              console.log(this.tableToJson());
              this.stat = true;
	          document.getElementById("endTurnButton").classList.toggle("hide");
            }
            else{
                alert("Invalid Selection");
            }
          } else {
            alert("This cell has already been filled.");
          }
        });
      });
      this.zero.addEventListener("click", () => this.takeZero());
    }
  
    over(){
        if(this.stat){
            delete this.dice;
        }
        return this.stat;
    }

    done(){
      return this.gameOver;
    }
    updateTotals() {

      var upperScore = 0;
      var lowerScore = 0;
  
      for (var i = 0; i < 6; i++) {
        upperScore += Number(this.cells[i].textContent) || 0;
      }
      for (var i = 6; i < 13; i++) {
        lowerScore += Number(this.cells[i].textContent) || 0;
      }
  
      this.upperTotal.textContent = upperScore;
      this.lowerTotal.textContent = lowerScore;
      this.grandTotal.textContent = upperScore + lowerScore;
  
      // Check if all cells are filled
      var allCellsFilled = Array.from(this.cells).every((cell) => cell.isFilled);
      if (allCellsFilled) {
        alert(`Game Over! Your total score is ${upperScore + lowerScore}`);
        this.gameOver = true;
      }
    }
     checkCategory(category, dice) {
        // Sort the dice values in ascending order
        dice.sort((a, b) => a - b);
      
        // Calculate the frequency of each value
        const frequencies = {};
        dice.forEach(value => {
          frequencies[value] = (frequencies[value] || 0) + 1;
        });
      
        // Check if the dice qualify for the selected category
        switch (category) {
          case "Ones":
            return frequencies[1] >= 1;
          case "Twos":
            return frequencies[2] >= 1;
          case "Threes":
            return frequencies[3] >= 1;
          case "Fours":
            return frequencies[4] >= 1;
          case "Fives":
            return frequencies[5] >= 1;
          case "Sixes":
            return frequencies[6] >= 1;
          case "Three-Of-A-Kind":
            return Object.values(frequencies).some(count => count >= 3);
          case "Four-Of-A-Kind":
            return Object.values(frequencies).some(count => count >= 4);
          case "Full House":
            return Object.values(frequencies).includes(2) && Object.values(frequencies).includes(3);
          case "Small Straight":
            return Object.keys(frequencies).join("").includes("1234") ||
                   Object.keys(frequencies).join("").includes("2345") ||
                   Object.keys(frequencies).join("").includes("3456");
          case "Large Straight":
            return Object.keys(frequencies).join("") === "12345" ||
                   Object.keys(frequencies).join("") === "23456";
          case "Yahtzee":
            return Object.values(frequencies).some(count => count === 5);
          case "Chance":
            return true; // Any combination qualifies for Chance
          default:
            return false; // Invalid category
        }
      }

      calculateScore(category, dice) {
        // Sort the dice values in ascending order
        if(!Array.isArray(dice)){
          return 0;
        }
        dice.sort((a, b) => a - b);
      
        // Calculate the frequency of each value
        const frequencies = {};
        dice.forEach(value => {
          frequencies[value] = (frequencies[value] || 0) + 1;
        });
      
        // Calculate the score for the selected category
        switch (category) {
          case "Ones":
            return frequencies[1] * 1;
          case "Twos":
            return frequencies[2] * 2;
          case "Threes":
            return frequencies[3] * 3;
          case "Fours":
            return frequencies[4] * 4;
          case "Fives":
            return frequencies[5] * 5;
          case "Sixes":
            return frequencies[6] * 6;
          case "Three-Of-A-Kind":
            return Object.entries(frequencies).some(([value, count]) => count >= 3) ?
                   dice.reduce((acc, value) => acc + value, 0) : 0;
          case "Four-Of-A-Kind":
            return Object.entries(frequencies).some(([value, count]) => count >= 4) ?
                   dice.reduce((acc, value) => acc + value, 0) : 0;
          case "Full House":
            return Object.values(frequencies).includes(2) && Object.values(frequencies).includes(3) ?
                   25 : 0;
          case "Small Straight":
            return Object.keys(frequencies).join("").includes("1234") ||
                   Object.keys(frequencies).join("").includes("2345") ||
                   Object.keys(frequencies).join("").includes("3456") ? 30 : 0;
          case "Large Straight":
            return Object.keys(frequencies).join("") === "12345" ||
                   Object.keys(frequencies).join("") === "23456" ? 40 : 0;
          case "Yahtzee":
            return Object.entries(frequencies).some(([value, count]) => count === 5) ? 50 : 0;
          case "Chance":
            return dice.reduce((acc, value) => acc + value, 0);
          default:
            return 0; // Invalid category
        }
      }
      takeZero(){
        this.skip = true;
        alert("Select what area you want to zero");
      }
      tableToJson() {
            // Get the table element
        var table = document.querySelector('table');

        // Initialize an empty array to store the table data
        var tableData = [];

        // Iterate over the table rows
        for (var i = 0; i < table.rows.length; i++) {
        var row = table.rows[i];
        var rowData = {};

        // Iterate over the table cells in the current row
        for (var j = 0; j < row.cells.length; j++) {
            var cell = row.cells[j];

            // Get the header value from the first row
            if (i === 0) {
            rowData['header' + j] = cell.textContent.trim();
            } else {
            // Get the cell value and use the corresponding header
            rowData['header' + j] = cell.textContent.trim();
            }
        }

        // Add the row data to the table data array
        tableData.push(rowData);
        }

// Convert the table data to JSON string
            return JSON.stringify(tableData);

      }
      


       populateTableFromJson(json) {
                // Assuming you have the JSON data stored in the 'jsonData' variable

            // Parse the JSON data
            var tableData = JSON.parse(json);

            // Get the table element
            var table = document.querySelector('table');

            // Iterate over the table rows
            for (var i = 0; i < table.rows.length; i++) {
            var row = table.rows[i];

            // Skip the first row since it contains the table headers
            if (i === 0) continue;

            // Get the corresponding row data from the parsed JSON
            var rowData = tableData[i ];

            // Iterate over the table cells in the current row
            for (var j = 0; j < row.cells.length; j++) {
                var cell = row.cells[j];

                // Get the header key for the current cell
                var headerKey = 'header' + j;

                // Update the cell content with the corresponding value from the JSON data
                cell.textContent = rowData[headerKey];
            }
            }}
  }

  class DiceGame {
    constructor() {
      this.diceElements = Array.from(document.querySelectorAll(".die"));
      this.rollButton = document.getElementById("rollButton");
      this.rollCount = 0;
      this.hasRolled = false;
      this.savedDice = [];
  
      this.diceImages = {
        1: "/yahtzee/images/dice1.png",
        2: "/yahtzee/images/dice2.png",
        3: "/yahtzee/images/dice3.png",
        4: "/yahtzee/images/dice4.png",
        5: "/yahtzee/images/dice5.png",
        6: "/yahtzee/images/dice6.png",
      };
  
      // Bind event handlers to the instance
      //this.rollDice = this.rollDice.bind(this);
      
      this.rollButton.addEventListener("click", () => this.rollDice());
  
      this.diceElements.forEach((die) => {
        die.addEventListener("click", () => {
          if (this.hasRolled) {
            die.classList.toggle("kept");
            this.updateSavedDice();
          }
        });
      });
    }
    resetDice() {
        this.rollCount = 0;
        this.hasRolled = false;
        this.savedDice = [];
       //this.diceElements = Array.from(document.querySelectorAll(".die"));
       this.diceElements.forEach((die) => {
       var img = die.firstChild;
       img.src = this.diceImages[1];
        die.classList.remove("kept");
      });
      }
    rollDice() {
        console.log(this.rollCount);
        console.log(this.hasRolled);
      if (this.rollCount < 3) {
        this.diceElements.forEach((die) => {
          if (!die.classList.contains("kept")) {
            var roll = Math.floor(Math.random() * 6) + 1;
            var img = die.firstChild;
            img.src = this.diceImages[roll];
            this.updateSavedDice();
          }
        });
        this.rollCount++;
        this.hasRolled = true;
      } else {
        alert("You have reached the maximum of 3 rolls. Please record your score.");
      }
    }
  
    updateSavedDice() {
        this.savedDice = this.diceElements.map((die) => {
          return parseInt(die.firstChild.getAttribute("src").match(/\d+/)[0]);
        });
    }
    getSavedDice() {
        return this.savedDice;
      }

  }
  
  window.onload = function () {
    document.getElementById("endTurnButton").classList.toggle("hide");
    var game = new Game();
  var startTurnButton = document.getElementById("startTurnButton");
  var endTurnButton = document.getElementById("endTurnButton");
  // Attach an event listener to the button
  startTurnButton.addEventListener("click", function() {
    // Call the game.startTurn() function
    game.startTurn();
  });
  endTurnButton.addEventListener("click", function() {
    // Call the game.startTurn() function
    game.endTurn();
  });
};