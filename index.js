$(function () {
    let startButton = $('#startButton');
    let cancelButton = $('#cancelButton');
    let nameInput = $('#name');
    let startPanel = $('#startPanel');
    let waitingPanel = $('#waitingPanel');
    let gamePanel = $('#gamePanel');

    let waitingPlayerName = $('#waitingPlayerName');
    let player1Name = $('#player1Name');
    let player2Name = $('#player2Name');

    let yourName = null;

    let checkingInterval = null;

    let phaseGuessing = $('.guessing');
    let phaseMakingGlue = $('.making_glue');
    let phaseWaitingGuessing = $('.waiting_guessing');
    let phaseWaitingGlue = $('.waiting_glue');

    startButton.click(() => {
        let name = nameInput.val();
        if (name) {
            requestStartGame(name);
        } else {
            alert('Введите имя игрока!');
        }
    });

    let turnWaitingInterval = null;

    cancelButton.click(() => {
        $.get('/ajax.php', {
            action: 'stop_waiting'
        }, result => {
            if (result === 'ok') {
                clearInterval(checkingInterval);
                waitingPanel.hide();
                startPanel.show();
            }
        });

    });

    function requestStartGame(name) {
        $.get('/ajax.php', {
            action: 'start_game',
            name: name
        }, result => {
            result = JSON.parse(result);
            if (result) {
                waitingPlayerName.text(name);
                startPanel.hide();
                waitingPanel.show();

                if (result.game) {
                    startGame(result.game);
                }

                runCheckingInterval();
            }
        });
    }

    function startGame(gameData) {
        let player1 = gameData.player1Name;
        let player2 = gameData.player2Name;
        player1Name.text(player1);
        player2Name.text(player2);
        $('#turns_passed').text('Прошло ходов: ' + gameData.turnsCount);
        $('#words_guessed').text('Слов отгадано: ' + gameData.agentsFound + ' из 15');
        $('.glueWordAndNumber').text(gameData.glueWord + ' ' + gameData.glueNumber);
        startPanel.hide();
        waitingPanel.hide();
        gamePanel.show();
        drawWords(gameData.words);
        turnOnPhase(gameData.phase);
        letWordsBeChosen(gameData);
    }

    function drawWords(words_array) {
        let cells = $('.main_table td');
        for (let i = 0; i < words_array.length; i++) {
            cells[i].innerHTML = words_array[i].word;
            if ((words_array[i].type_me == 'killer' && words_array[i].guessed_me) || (words_array[i].type_partner == 'killer' && words_array[i].guessed_partner)) {
                cells[i].classList.add('killer_cell_guessed');
            } else if ((words_array[i].type_me == 'agent' && words_array[i].guessed_me) || (words_array[i].type_partner == 'agent' && words_array[i].guessed_partner)) {
                cells[i].classList.add('agent_cell_guessed');
            } else if (words_array[i].type_partner == 'killer' && !words_array[i].guessed_partner) {
                cells[i].classList.add('killer_cell');
            } else if (words_array[i].type_partner == 'agent' && !words_array[i].guessed_partner) {
                cells[i].classList.add('agent_cell');
            } else if (words_array[i].type_partner == 'neutral' && !words_array[i].guessed_partner) {
                cells[i].classList.add('neutral_cell');
            }

            if (words_array[i].type_me == 'neutral' && words_array[i].guessed_me) {
                $('.neutral_guessed_my').clone().appendTo(cells[i]);
            }
            if (words_array[i].type_partner == 'neutral' && words_array[i].guessed_partner) {
                $('.neutral_guessed_partner').clone().appendTo(cells[i]);
            }
        }
    }

    // Включаем нужную панель в зависимости от фазы
    function turnOnPhase(phase) {
        phaseGuessing.hide();
        phaseMakingGlue.hide();
        phaseWaitingGuessing.hide();
        phaseWaitingGlue.hide();

        switch (phase) {
            case 'guess':
                clearInterval(turnWaitingInterval);
                turnWaitingInterval = null;
                phaseGuessing.show();
                break;
            case 'glue':
                clearInterval(turnWaitingInterval);
                turnWaitingInterval = null;
                phaseMakingGlue.show();
                break;
            case 'waitingGuess':
                phaseWaitingGuessing.show();
                startCheckingTurn();
                break;
            case 'waitingGlue':
                phaseWaitingGlue.show();
                startCheckingTurn();
                break;
        }
    }

    function letWordsBeChosen (gameData) {
        if (gameData.phase == 'guess') {
            for (let i = 1; i <= gameData.words.length; i++) {
                let cell_class = '.cell' + i;
                if (!gameData.words[i-1].guessed_me) {
                    if (!gameData.words[i-1].guessed_partner) {
                        $(cell_class).addClass('clickable');
                    } else if (gameData.words[i-1].type_partner != 'killer' && gameData.words[i-1].type_partner != 'agent') {
                        $(cell_class).addClass('clickable');
                    }
                }
            }
        }
    }

    function checkForStart() {
        $.get('/ajax.php', {
            action: 'check_start'
        }, result => {
            result = JSON.parse(result);
            if (result && result.game) {
                startGame(result.game);
                clearInterval(checkingInterval);
            }
        });
    }

    function initStatus() {
        $.get('/ajax.php', {
            action: 'init_status',
        }, result => {
            result = JSON.parse(result);
            if (result) {
                if (result.is_waiting) {
                    waitingPlayerName.text(result.name);
                    startPanel.hide();
                    waitingPanel.show();
                } else if (result.is_playing) {
                    startGame(result.is_playing);
                }

                if (result.name) {
                    nameInput.val(result.name);
                }
            }
        });
    }

    $('.making_glue_button').click(function() {
        let glueWord   = $('.glue_text').val();
        let glueNumber = parseInt($('.glue_number').val());

        if (glueWord.length < 2) {
            alert('Введите подсказку-слово');
            return false;
        } else if (glueNumber < 1) {
            alert('Введите подсказу-число');
            return false;
        }

        $(this).hide();

        $.get('/ajax.php', {
            action: 'make_glue',
            word: glueWord,
            number: glueNumber
        }, result => {
            result = JSON.parse(result);
            if (result.game) {
                startGame(result.game);
            }

            $(this).show();
        });

    });

    function runCheckingInterval()
    {
        checkingInterval = setInterval(checkForStart, 5000);
    }


    function checkTurn() {
        $.get('/ajax.php', {
            action: 'check_turn'
        }, result => {
            result = JSON.parse(result);
            if (result && result.game) {
                startGame(result.game);
            }
        });
    }

    function startCheckingTurn()
    {
        if (!turnWaitingInterval) {
            turnWaitingInterval = setInterval(checkTurn, 3000);
        }
    }

    $('.toggleColored').click(() => {
        $('.main_table').toggleClass('colored');
    });

    initStatus();

});