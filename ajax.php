<?php

require_once 'common.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'start_game':
            $cache = readCache();
            $_SESSION['name'] = $_GET['name'];
            $cache['searching'][] = $_GET['name'];
            $cache['searching'] = array_unique($cache['searching']);
            writeCache($cache);

            $isStarted = checkWaitingPlayers();
            echo $isStarted ? json_encode($isStarted) : '"Waiting"';
            break;

        case 'check_start':
            $isStarted = checkWaitingPlayers();
            echo json_encode($isStarted);
            break;

        case 'stop_waiting':
            removeFromWaiting();
            echo 'ok';
            break;

        case 'init_status':
            $status = [
                'name' => $_SESSION['name'],
                'is_waiting' => isNowWaiting(),
                'is_playing' => checkWaitingPlayers()
            ];
            echo json_encode($status);
            break;
    }
}