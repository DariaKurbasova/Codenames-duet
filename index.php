<?php

session_start();

?>

<div id="startPanel">
    <label>
        Ваше имя:
        <input id="name">
    </label>

    <button id="startButton">
        Начать игру
    </button>
</div>

<div id="waitingPanel" style="display: none;">
    <h2>Ожидание игры...</h2>
    <h3>Вы вошли как: <span id="waitingPlayerName"></span></h3>
    <button id="cancelButton">
        Отмена
    </button>
</div>

<div id="gamePanel" style="display: none;">

    <h1>Игра началась!</h1>
    <h2>
        <span id="player1Name"></span> vs <span id="player2Name"></span>
    </h2>
</div>

<script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous">
</script>
<script src="index.js"></script>
