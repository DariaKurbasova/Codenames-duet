<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <title>Codenames Duet</title>
    <link rel="icon" type="image/png" href="img/favicon.png" />
    <link rel="stylesheet" href="styles.css">
</head>

<body>


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

<section id="gamePanel" style="display: none;">
    <h1>
        <span id="player1Name"></span> vs <span id="player2Name"></span>
    </h1>

    <h2>Прошло ходов: х</h2>
    <h2>Слов отгадано: у из 15</h2>

    <table class = "main_table colored">
        <tr>
            <td class = "cell01"></td>
            <td class = "cell02"></td>
            <td class = "cell03"></td>
            <td class = "cell04"></td>
            <td class = "cell05"></td>
        </tr>
        <tr>
            <td class = "cell06"></td>
            <td class = "cell07"></td>
            <td class = "cell08"></td>
            <td class = "cell09"></td>
            <td class = "cell10"></td>
        </tr>
        <tr>
            <td class = "cell11"></td>
            <td class = "cell12"></td>
            <td class = "cell13"></td>
            <td class = "cell14"></td>
            <td class = "cell15"></td>
        </tr>
        <tr>
            <td class = "cell16"></td>
            <td class = "cell17"></td>
            <td class = "cell18"></td>
            <td class = "cell19"></td>
            <td class = "cell20"></td>
        </tr>
        <tr>
            <td class = "cell21"></td>
            <td class = "cell22"></td>
            <td class = "cell23"></td>
            <td class = "cell24"></td>
            <td class = "cell25"></td>
        </tr>
    </table>

    <!-- Шаблоны значков для открытых нейтральных слов -->
    <div style="display: none">
        <div class = "neutral_guessed_partner" title="Это нейтральное слово пытался угадать ваш партнер"></div>
        <div class = "neutral_guessed_my" title="Вы пытались угадать это слово"></div>
    </div>


    <div class="button_center">
        <button class="toggleColored">Вкл/выкл раскраску</button>
    </div>

    <div class= "phase">
        <div class = "guessing hidden">
            <p class = "glue">Подсказка: котики 3</p>
            <button class = "stop_guessing_button">Завершить ход</button>
        </div>

        <div class = "making_glue">
            <form>
                <label>Введите подсказку
                    <input class = "glue_text" type = "text" name = "glue_text">
                </label>
                <input class = "glue_number" type = "text" name = "glue_number">
                <button class = "making_glue_button">Отправить подсказку</button>
            </form>
        </div>

        <div class = "waiting_guessing hidden">
            <p>Другой игрок отградывает слова</p>
        </div>

        <div class = "waiting_glue hidden">
            <p>Ждите подсказки от другого игрока</p>
        </div>
    </div>
</section>

<script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous">
</script>
<script src="index.js"></script>

</body>
</html>