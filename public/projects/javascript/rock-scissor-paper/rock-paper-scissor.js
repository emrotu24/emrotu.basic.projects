let score = JSON.parse(localStorage.getItem('score')) || {
  wins: 0,
  losses: 0,
  ties: 0,
}; // or operator || semplifica il codice di sotto con lo stesso risultato//

updateScoreElement();
/*
            if (!score) { // score === null // (!not quindi se score è falso)
                score = {
                    wins: 0,
                    losses: 0,
                    ties: 0,
                };
            }
            */
let intervalId;


document.querySelector('.js-paper-button').addEventListener('click', () => {
  playGame('paper');
});

document.querySelector('.js-rock-button').addEventListener('click', () => {
  playGame('rock');
});

document.querySelector('.js-scissor-button').addEventListener('click', () => {
  playGame('scissor');
});

document.body.addEventListener('keydown', (event) => {
  if (event.key === 'r') {
    playGame('rock');
  } else if (event.key === 'p') {
    playGame('paper');
  } else if (event.key === 's') {
    playGame('scissor');
  }
});

/* function permette di avere il codice più pulito inserendo il codice
            dentro la funzione una sola volta e richiamandolo quando necessario */
function playGame(playerMove) {
  const computerMove = pickComputerMove();

  let result = '';

  if (playerMove === 'scissor') {
    //se scelgo scissor esegue codice sotto//
    if (computerMove === 'rock') {
      result = 'YOU LOSE';
    } else if (computerMove === 'paper') {
      result = 'YOU WIN!';
    } else if (computerMove === 'scissor') {
      result = 'TIE!';
    }
  } else if (playerMove === 'paper') {
    //se scelgo paper esegue codice sotto//
    if (computerMove === 'rock') {
      result = 'YOU WIN!';
    } else if (computerMove === 'paper') {
      result = 'TIE!';
    } else if (computerMove === 'scissor') {
      result = 'YOU LOSE';
    }
  } else if (playerMove === 'rock') {
    //se scelgo rock esegue codice sotto//
    if (computerMove === 'rock') {
      result = 'TIE!';
    } else if (computerMove === 'paper') {
      result = 'YOU LOSE';
    } else if (computerMove === 'scissor') {
      result = 'YOU WIN!';
    }
  }

  if (result === 'YOU WIN!') {
    score.wins += 1;
  } else if (result === 'YOU LOSE') {
    score.losses += 1;
  } else if (result === 'TIE!') {
    score.ties += 1;
  }

  localStorage.setItem('score', JSON.stringify(score));

  updateScoreElement();

  document.querySelector('.js-result').innerHTML = result;

  document.querySelector('.js-moves').innerHTML = `You
                    <img src="Images/${playerMove}.png" alt="" class="move-icon" >
                    <img src="Images/${computerMove}.png" alt="" class="move-icon" >
                    Computer`;
}

function updateScoreElement() {
  document.querySelector(
    '.js-score'
  ).innerHTML = `Wins: ${score.wins}, Losses: ${score.losses}, Ties: ${score.ties}`;
}

function pickComputerMove() {
  //SCOPE variabili esistono solo dentro le parentesi graffe//
  const randomNumber = Math.random();

  let computerMove = '';

  if (randomNumber >= 0 && randomNumber < 1 / 3) {
    computerMove = 'rock';
  } else if (randomNumber >= 1 / 3 && randomNumber < 2 / 3) {
    computerMove = 'paper';
  } else if (randomNumber >= 2 / 3 && randomNumber < 1) {
    computerMove = 'scissor';
  }
  //return esegue il valore quando richiamiamo la funzione//
  return computerMove; //usare variabile dentro scope ed usare return evita conflitti di nome //
  //non esegue altro codice dopo return//
  //console.log('hello') non verrò eseguito//
}
