<?php

require_once 'common.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'start_game':
            putToWaiting($_GET['name']);
            echo gameToJsonAnswer(checkWaitingPlayers());
            break;

        case 'check_start':
            echo gameToJsonAnswer(checkWaitingPlayers());
            break;

        case 'stop_waiting':
            removeFromWaiting();
            echo 'ok';
            break;

        case 'init_status':
            $game = checkWaitingPlayers();
            $status = [
                'name' => $_SESSION['name'],
                'is_waiting' => isNowWaiting(),
                'is_playing' => ($game && $game instanceof Game) ? $game->toArray() : false
            ];
            echo json_encode($status);
            break;
    }
}

function gameToJsonAnswer($game) {
    return ($game && $game instanceof Game) ? json_encode([
        'game' => $game->toArray()
    ]) : '"Waiting"';
}