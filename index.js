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
        // todo - кол-во ходов и отгаданных слов
        startPanel.hide();
        waitingPanel.hide();
        gamePanel.show();
        drawWords(gameData.words);
        turnOnPhase(gameData.phase);
    }

    function drawWords(words_array) {
        debugger;
        for (let i = 0; i < words_array.length; i++) {
            $('.main_table td')[i].innerHTML = words_array[i].word;
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
                phaseGuessing.show();
                break;
            case 'glue':
                phaseMakingGlue.show();
                break;
            case 'waitingGuess':
                phaseWaitingGuessing.show();
                break;
            case 'waitingGlue':
                phaseWaitingGlue.show();
                break;
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

    function runCheckingInterval()
    {
        checkingInterval = setInterval(checkForStart, 5000);
    }

    $('.toggleColored').click(() => {
        $('.main_table').toggleClass('colored');
    });

    initStatus();

});