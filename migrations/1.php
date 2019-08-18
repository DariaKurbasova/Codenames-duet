<?php

require_once '../db.php';

$sql = <<<SQL
DROP TABLE IF EXISTS game_words;
DROP TABLE IF EXISTS games;

CREATE TABLE games (
  id smallint UNSIGNED AUTO_INCREMENT,
  status enum('in_process', 'won', 'lost') NOT NULL ,
  phase enum('hint1', 'hint2', 'guess1', 'guess2'),
  started_at timestamp DEFAULT CURRENT_TIMESTAMP,
  player1 varchar(50) NOT NULL,
  player2 varchar(50) NOT NULL,
  turns_count tinyint DEFAULT 0,
  hint_word varchar (40),
  hint_number tinyint,
  PRIMARY KEY (id)
);

CREATE TABLE game_words (
  game_id smallint UNSIGNED,
  cell_number tinyint NOT NULL,
  word varchar (40) NOT NULL,
  type_for_player1 enum('agent', 'killer', 'neutral'),
  type_for_player2 enum('agent', 'killer', 'neutral'),
  guessed_by_player1 boolean DEFAULT false,
  guessed_by_player2 boolean DEFAULT false,
  PRIMARY KEY (game_id, cell_number),
  FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE ON UPDATE CASCADE
);
SQL;

$database->multi_query($sql);
echo 'Ok!';