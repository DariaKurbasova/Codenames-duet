<?php

require_once 'db.php';

$sql = <<<SQL
DROP TABLE IF EXISTS games;
CREATE TABLE games (
  id smallint UNSIGNED AUTO_INCREMENT,
  status enum('in_process', 'won', 'lost') NOT NULL ,
  phase enum('hint1', 'hint2', 'guess1', 'guess2'),
  started_at timestamp DEFAULT CURRENT_TIMESTAMP,
  player1 varchar(50),
  player2 varchar(50),
  turns_count tinyint DEFAULT 0,
  PRIMARY KEY (id)
);

DROP TABLE IF EXISTS game_words;
CREATE TABLE game_words (
  game_id smallint UNSIGNED,
  cell_number tinyint,
  words varchar (40),
  type_for_player1 enum('agent', 'killer', 'empty'),
  type_for_player2 enum('agent', 'killer', 'empty'),
  guessed_status json,
  PRIMARY KEY (game_id, cell_number),
  FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE ON UPDATE CASCADE
);
SQL;

$database->multi_query($sql);
echo 'Ok!';