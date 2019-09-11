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
    <div class = "start-game">
        <label>
        Ваше имя:
        <input class = "your-name" type="text" id="name">
        </label>

        <button id="startButton">
            Начать игру
        </button>
    </div>
    <p class="description">Чтобы начать игру, вы и другой игрок должны войти под своими именами с разных устройств.</p>

    <img width="300" src="img/board-game.png">
    <div class = "rules">
        <h1>Как играть?</h1>
        <p>
            Правила изучаются быстро и легко — те, кто уже знаком с настольной игрой, сориентируются моментально, кто ни разу не играл в Codenames – разберутся минут за 10-15. Полную версию правил (в т.ч. о том, как правильно загадывать слова) можно найти <a href="https://cdn.mosigra.ru/mosigra.product.other/547/813/kodovye_imena_duet.pdf">здесь</a>.
        </p>
        <ol>
            <li>
                На игровом поле выкладывается квадрат 5х5 из карточек, выбранных случайным образом.
            </li>
            <li>
                Компаньоны садятся друг напротив друга и устанавливают ключ в специально предназначенную подставку так, чтобы каждый видел только одну его сторону. При этом получается, что одна и та же карточка на игровом поле для одного участника может быть агентом, а для другого – убийцей или мирным жителем, и только 3 слова отмечены зеленым цветом с обеих сторон.
            </li>
            <li>
                Вместе участники должны отгадать 15 зеленых слов за 9 или менее ходов. Допускается на стадии освоения игры продление времени до 11 ходов.
            </li>
            <li>
                Задача каждого участника объединить одним словом как можно большее число своих агентов, не натолкнув в тоже время оппонента на убийцу или мирного жителя. Встреча с убийцей заканчивает игру немедленным поражением обоих участников, а встреча с мирным жителем снижает общие шансы на успех.
            </li>
        </ol>
    </div>
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

    <h2 id = "turns_passed"></h2>
    <h2 id = "words_guessed"></h2>

    <table class = "main_table colored">
        <tr>
            <td class = "cell1"></td>
            <td class = "cell2"></td>
            <td class = "cell3"></td>
            <td class = "cell4"></td>
            <td class = "cell5"></td>
        </tr>
        <tr>
            <td class = "cell6"></td>
            <td class = "cell7"></td>
            <td class = "cell8"></td>
            <td class = "cell9"></td>
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
        <div class = "guessing">
            <p class = "glue">Подсказка: <span class="glueWordAndNumber">котики 3</span></p>
            <button class = "stop_guessing_button">Завершить ход</button>
        </div>

        <div class = "making_glue">
            <label>Введите подсказку
                <input class = "glue_text" type = "text" name = "glue_text">
            </label>
            <input class = "glue_number" type = "number" min="1" max="9" name = "glue_number">
            <button class = "making_glue_button">Отправить подсказку</button>
        </div>

        <div class = "waiting_guessing">
            <p>Другой игрок отгадывает слова</p>
        </div>

        <div class = "waiting_glue">
            <p>Ждите подсказки от другого игрока</p>
        </div>
    </div>

    <div class="winPanel" style="display: none;">
        <h2>
            Вы победили!
        </h2>
        Затрачено ходов: <span class="winTurnsCount"></span>
        <br>
        Открыто нейтралов: <span class="winNeutralsCount"></span>
        <br>
        Затрачено времени: <span class="winTimeSpent">пока не измеряется</span>
        <br>
        <button class="restartGame">Начать заново</button>
    </div>

    <div class="losePanel" style="display: none;">
        <h2>
            Вы потерпели поражение!
        </h2>
        Игрок <span class="losePlayerName"></span> нажал на слово <span class="loseWord"></span>
        <br>
        <button class="restartGame">Начать заново</button>
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