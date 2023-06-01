class Game {
    static players = [];
    constructor(){
        this.numP = document.getElementById('numberPlayers');
        this.dice = new Dice(5);
        this.currentPlayerIndex = 0;
        this.highScore = 0;
        this.winner = "";
    }
    
    start(){
        //Will need to read in from SQL db for username/passwords
        //Load new web page, buttons to press: addPlayer, createPlayer, play Game
        window.location = "start.html";
    }
    
    play(){
        if(this.gameOver()){
            for(player of this.players){
                player.setScore();
                player.saveCard();
                let score= player.getScore();
                if(score > this.dicehighScore){
                    this.highScore=score;
                    this.winner=player.getName();
                    
                }}
                //This should be moved to a view and this function can pass the values to the view 
                document.getElementById("playAgain").style.visibility = "visible";
                document.getElementById("gameWinnerName").innerHTML = this.winner;
                document.getElementById("gameWinnerScore").innerHTML = this.highScore;
        }
            if(!this.players[this.currentPlayerIndex].isPlayerDone()){
            this.players[this.currentPlayerIndex].takeTurn(this.dice);//This will display the board and the dice, give user option to view card (toggle)
            }
            this.currentPlayerIndex = (currentPlayerIndex+1) % numP; 
        }

    
  
    
    end(){
        //move to view 
        window.location = "Goodbye.html";
    }
    
    gameOver(){
        let end=true;
        for(player of this.players){
            if(!player.isPlayerDone()){
                end=false;
                break;
            }
        }
        return end;
    }
    
    addPlayer(){
        if(this.numP<=4){
            //Need a function that will take login credentials to access current
            //player's data, we will need to retrieve the name, password, and
            //privilege level, this will be done in PHP
            let p = new Player(document.getElementById("username"));
            this.players.push(p);
            this.numP=this.players.length;
        }
        else{
            //Display warning no more players are able to join
            document.getElementById("warning").innerHTML = 
                    "Maximum number of players reached.";
        }
    }
    
    createPlayer(){
        player = new Player();
        //Get username from doc
        username = document.getElementById("username").value;
        //Get password from doc
        password = document.getElementById("password").value;
        //Check if username already exist in database
        if(/*username not exist*/){
            //Use PHP to access the DB
        }
        else{
            document.getElementById("warning").innerHTML = 
                    "Username already exist";
        }
    }
    //this can be in a validator class and not here 
    passwordFormat(passwd){
        passSet=0;
        var len = passwd.length;
        if(len>8)
            passSet|=16;
        if(passwd.match(/^[A-Z]+$/))
            passSet|=8;
        if(passwd.match(/^[a-z]+$/))
            passSet|=4;
        if(passwd.match(/^\d+$/))
            passSet|=2;
        if(passwd.match(/^[!@#$&]+$/))
            passSet|=1;
        
        if(passSet!==31){
            //Output warning message that password criteria not met
            return false;
        }
        return true;
    }
}