<?php

function readCache() {
    $contents = file_get_contents('cache.json');
    $contents = $contents ? json_decode($contents, true) : [];
    return $contents;
}

function writeCache($contents) {
    $contents = json_encode($contents);
    file_put_contents('cache.json', $contents);
}

function checkWaitingPlayers() {
    $data = readCache();
    $searching = &$data['searching'];
    if (!empty($searching)) {
        while (count($searching) >= 2) {
            $player1 = array_shift($searching);
            $player2 = array_shift($searching);
            $data['started'][] = [$player1, $player2];
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
                return $startedGame;
            }
        }
    }
    return false;
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
