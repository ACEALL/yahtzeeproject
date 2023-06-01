var Player = {
    score: 0,
    userName: " ",
    playerID: 0,



//need to get score card associated with player
constructor( name,pID){
    this.userName=name;
    this.playerID=pID;
},

resetDKeep: function(dieKeep, keep){
    for(let i=0;i<keep;i++){
        dieKeep[i]=-1;
    }
},

setName:function(name){
    this.userName=name;
},

throwDice: function(dice, keep){
    if(keep==0) dice.rollDice();
    else{
       var  diceKept = holdDice(keep)
        while(diceKept == false){
         diceKept=holdDice(keep);
        }
        //Create OUTPUT FOR DISPLAY

        // cout<<setw(21)<<""<<"Dice Kept: ";
        // for(int i=0;i<keep;i++){
        //     cout<<diceKept[i]+1<<" ";
        // }
        //cout<<endl;
        dice.rollDice(diceKept,keep);
    }
},
takeTurn: function(dice){
    var turns=0;
    var keep=0;
    // cout<<"\n\n";
    // cout<<setw(21)<<""<<userName<<"'s Turn:"<<endl;
    while(keep!=5 && turns<3){
        throwDice(dice,keep);
        turns++;
        dice.printDice();
        if(turns < 3)keep = keepDice();
    }
    
    selCat(dice);
    saveCard();
},

holdDice: function(numHold){
    var count = 0;
    var buffer = new Array();
    var input = document.getElementById("holdDice").value.split(' ');
    for(let input of userHolds){
            if(input > 5 || input < 1){
                //error control needed
                return false;
            } 
            if(buffer.includes(input)){
                //error control messege 
                return false;
            }
            else{
            buffer.push(input);
            }
            count++;
            if(count > 5 || numHold != count){
                //error control messege
                return false;
            }

        }
    return buffer;
},

keepDice: function(){
    var keep = document.getElementById("keep").value;
    //this should be visable in the
    // cout<<setw(21)<<""<<"Enter how may dice you would like to keep:"<<endl;
    // cout<<setw(21)<<"";
    if(keep < 0 || keep > 5)keep = this.keepDice();
    return keep;

},
selCat:function(dice){
    do{
        card.printCategories();
        //field on HTML
        let category = document.getElementById("category").value;
        let filled = card.setScoreCell(category,dice);
        if(!filled){
            //error control
        }
    }while(!filled);
},
printCard:function(){
    card.printScoreCard();
},
saveCard: function(){
    card.saveCard(this.userName,this.playerID);
},
setScore: function(){
    this.score=card.getScore();
},
isPlayerDone: function(){
    let done=card.isCardFull();
    return done;
},

debugPlayer: function(dice){
    dice.debugDice();
    selCat(dice);
    card.debugCard();
    printCard();
},


}