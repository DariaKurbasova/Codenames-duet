<?php

$database = new mysqli('localhost', 'root', '', 'codenames');
// testDb();

function testDb() {
    global $database;
    $result = $database->query('select 1');
    if ($result->fetch_array()[0] == '1') {
        echo 'DB is ok!';
    } else {
        echo 'You have some troubles';
    }
}
