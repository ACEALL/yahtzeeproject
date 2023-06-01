//controller class
var Player = {
    username: "",
    scoreCard: null,
    turn: null,
    
    constructor(user){
        this.username = user;
        this.ScoreCard = ScoreCard.getNew();
    },
    startPlayerTurn: function(){
        this.turn =new PlayerTurn(this.scoreCard);
        this.turn.throwDice();
    },
    //when Selecting hold
    hold: function(){
        var userHolds = document.getElementById("holdDice").value.split(' ');
        for(let input of userHolds){
            if(input > 5 || input < 1){
                //error control needed
            } 
        }
        this.turn.holdDice(userHolds); 
        this.turn.throwDice();
        //will have to make sure this doesn't run to fast if so then we will have to use something like await
        this.isTurnDone();
    },
    //When selecting done(not rerolling) or run out of turns 
    done: function(){
        //Display ScoreCard and What is Avaliable to choose
        ScoreCard.Avalible(this.scoreCard);
        //Don't know if Input should come from here but could be something from below
        // var selection = document.getElementById("catChoice").value; 
        // if(this.scoreCard.hasOwnProperty(selection)){
        //     this.turn.selCat(selection);
               this.scoreCard = this.turn.done();
        // }  
    },
    isTurnDone: function(){
        if(this.turn.turnUsed >= 3) this.done();
    }
    
}
//model class
var PlayerTurn = {
    rolls: 0,
    scoreCard: null,
    playerDice = {
        1:{value: 0, status:"roll"},
        2:{value: 0, status:"roll"},
        3:{value: 0, status:"roll"},
        4:{value: 0, status:"roll"},
        5:{value: 0, status:"roll"}
    },
    constructor(card){
        this.scoreCard = card;
    },
    throwDice: function(){
            this.playerDice = dice.roll(this.playerDice);
            rolls++;
    },
    //keep should be an array of the dice to keep ex [1,3,5]
    holdDice: function(keep){
        for(let d of keep){
            this.playerDice[d].status = "hold";
        }
    },

    selCat: function(choice){
        var score = 0;
        if(this.scoreCard.ScoreCardChoices[choice] == null){
            for(let d in this.playerDice){
                score += this.playerDice[d].value;
            }
            this.scoreCard.ScoreCardChoices[choice] = score;
        }
        else{

            //ERROR CONTROL NEEDED 
        }
    },
    done: function(){
        return this.scoreCard;
    },

    turnUsed: function(){
        return this.rolls;
    }
}


// Not sure what we will need to show for view on this side yet(Card and Dice will be the main veiws during turns )
var PlayerTurnView = {

}

//this is a model class
var Dice = {
    roll: function(playerDice){
        diceValue = new Array;
        for(d in playerDice){
            if(playerDice[d].status == "roll"){
                playerDice[d].value = Math.floor(Math.random() * 6) + 1;
            }
            diceValue.push(playerDice[d].value);
        }
        //call the view class
        DiceView.displayDice(diceValue);
        return playerDice;
    }
}
//JSON OBJECT For Indvidual Game ScoreCard
var PlayerScoreCard = {
    //Upper Section
    "ScoreCardChoices":{
            "Aces":null,
            "Twos":null,
            "Threes":null,
            "Fours":null, 
            "Fives":null, 
            "Sixes":null,
        //Lower Section
            "3 Of A Kind":null, 
            "4 Of A Kind":null, 
            "Full House":null, 
            "Sm Straight":null, 
            "Lg Straight":null, 
            "Yahtzee":null, 
            "Chance":null, 
        
    },
    "Totals": {
     "Total Lower":null, 
     "Total Upper":null, 
     "Grand Total":null, 
    },
    "Bonus":null,
    "Yahtzee BONUS":null, 
}