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

    let chekingInterval = null;

    startButton.click((event) => {
        let name = nameInput.val();
        if (name) {
            requestStartGame(name);
        } else {
            alert('Введите имя игрока!');
        }
    });

    cancelButton.click((event) => {
        $.get('/ajax.php', {
            action: 'stop_waiting'
        }, result => {
            if (result === 'ok') {
                clearInterval(chekingInterval);
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

                if ($.isArray(result)) {
                    startGame(result);
                }

                runCheckingInterval();
            }
        });
    }

    function startGame(players) {
        let player1 = players[0];
        let player2 = players[1];
        player1Name.text(player1);
        player2Name.text(player2);
        startPanel.hide();
        waitingPanel.hide();
        gamePanel.show();
    }

    function checkForStart() {
        $.get('/ajax.php', {
            action: 'check_start'
        }, result => {
            result = JSON.parse(result);
            if (result && $.isArray(result)) {
                startGame(result);
                clearInterval(chekingInterval);
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

                if (result.name) {888888888888888
                    nameInput.val(result.name);
                }
            }
        });
    }

    function runCheckingInterval()
    {
        chekingInterval = setInterval(checkForStart, 5000);
    }

    initStatus();

});