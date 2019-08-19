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

        case 'make_glue':
            $game = Game::getFromDb();
            $glueNumber = $_GET['number'];
            $glueWord = $_GET['word'];
            if ($game && $glueNumber >= 1 && $glueWord) {
                $game->makeGlue($glueWord, $glueNumber);
                $answer = [
                    'success' => true,
                    'game' => $game->toArray()
                ];
            } else {
                $answer = [
                    'success' => false
                ];
            }

            echo json_encode($answer);
            break;

        case 'check_turn':
            $game = Game::getFromDb();
            echo gameToJsonAnswer($game);
            break;
    }
}

function gameToJsonAnswer($game) {
    return ($game && $game instanceof Game) ? json_encode([
        'game' => $game->toArray()
    ]) : '"Waiting"';
}