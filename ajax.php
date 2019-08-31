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

        case 'guess_word':
            $game = Game::getFromDb();
            $cellNumber = (int)$_GET['cell_number'];
            if ($game && $cellNumber >= 1 && $cellNumber <= 25) {
                $success = $game->guessWord($cellNumber);
                $answer = [
                    'success' => $success,
                    'game' => $game->toArray()
                ];
            } else {
                $answer = [
                    'success' => false
                ];
            }

            echo json_encode($answer);
            break;

        case 'end_turn':
            $game = Game::getFromDb();
            if ($game) {
                $success = $game->stopGuessing();
                $answer = [
                    'success' => $success,
                    'game' => $game->toArray()
                ];
            } else {
                $answer = [
                    'success' => false
                ];
            }
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