<?php

require_once 'Game.php';

const CACHE_FILENAME = 'cache.json';

function readCache() {
    $contents = file_get_contents(CACHE_FILENAME);
    $contents = $contents ? json_decode($contents, true) : [];
    return $contents;
}

function writeCache($contents) {
    $contents = json_encode($contents);
    file_put_contents(CACHE_FILENAME, $contents);
}

function putToWaiting($name) {
    $_SESSION['name'] = $name;
    $cache = readCache();
    $cache['searching'][] = $name;
    $cache['searching'] = array_unique($cache['searching']);
    writeCache($cache);
}

function checkWaitingPlayers() {
    $data = readCache();
    $searching = &$data['searching'];
    if (!empty($searching)) {
        while (count($searching) >= 2) {
            $player1 = array_shift($searching);
            $player2 = array_shift($searching);

            // Создаём игру. В сессию здесь ничего не пишем, т.к. это будет работать лишь для одного игрока, а игра создаётся для обоих
            $game = Game::generateNewGame($player1, $player2);
            $data['started'][] = [$player1, $player2, $game->id];
        }
        writeCache($data);
    }

    return checkYourGameStarted();
}

function checkYourGameStarted() {
    $data = readCache();

    if (!empty($data['started'])) {
        foreach ($data['started'] as $startedGame) {
            if ($startedGame[0] == $_SESSION['name'] || $startedGame[1] == $_SESSION['name']) {
                // Есть начатая игра! Чтобы выбрать игру из базы, запишем в сессию её ID
                $_SESSION['game_id'] = $startedGame[2];
                $_SESSION['player_index'] = ($startedGame[0] == $_SESSION['name']) ? 1 : 2;
            }
        }
    }

    $game = Game::getFromDb();
    return $game;
}

function removeFromWaiting() {
    $data = readCache();
    if (!empty($data['searching'])) {
        $data['searching'] = array_diff($data['searching'], [$_SESSION['name']]);
        writeCache($data);
    }
}

function isNowWaiting() {
    if (!empty($_SESSION['name'])) {
        $data = readCache();
        if (!empty($data['waiting'])) {
            return in_array($_SESSION['name'], $data['waiting']);
        } else {
            return false;
        }
    } else {
        return false;
    }
}
