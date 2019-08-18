<?php

require_once 'common.php';
require_once 'Words.php';

class Game
{
    // Атрибуты, соответсвующие полям из базы данных
    public $id;
    public $phase;
    public $status;
    public $player1Name;
    public $player2Name;
    public $turnsCount;

    /** @var int  Кто вы, игрок 1 или игрок 2 */
    public $yourPlayerIndex;

    public $words = [];

    const STATUS_IN_PROCESS = 'in_process';
    const STATUS_WON = 'won';
    const STATUS_LOST = 'lost';

    const PHASE_PLAYER1_HINT  = 'hint1';
    const PHASE_PLAYER2_HINT  = 'hint2';
    const PHASE_PLAYER1_GUESS = 'guess1';
    const PHASE_PLAYER2_GUESS = 'guess2';

    public static function getFromDb() {
        global $database;

        // Если в сессии игрока есть ID игры, то находим её
        $gameId = (int)$_SESSION['game_id'];
        if ($gameId) {
            $gameData = $database->query("SELECT * FROM games WHERE id = {$gameId}");

            $gameData = $gameData->fetch_assoc();
            if ($gameData) {

                $game = new self();
                $game->yourPlayerIndex = $_SESSION['player_index'];

                // заполняем поля объекта
                $game->id = $gameData['id'];
                $game->player1Name = $gameData['player1'];
                $game->player2Name = $gameData['player2'];
                $game->status = $gameData['status'];
                $game->phase = $gameData['phase'];
                $game->turnsCount = $gameData['turns_count'];

                // todo - запрашиваем слова и считаем по ним нужную инфу
                return $game;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function generateNewGame($player1Name, $player2Name)
    {
        $game = new self();
        $game->player1Name = $player1Name;
        $game->player2Name = $player2Name;
        $game->status = self::STATUS_IN_PROCESS;
        // Случайно определяем, кто загадывает первым
        $game->phase = rand(0, 1) ? self::PHASE_PLAYER1_HINT : self::PHASE_PLAYER2_HINT;
        $game->createInDatabase();

        $wordsGenerator = new Words();
        $game->words = $wordsGenerator->generate();
        $game->createWordsInDb();

        return $game;
    }

    private function createInDatabase()
    {
        global $database;
        $sql = "
            INSERT INTO games (status, phase, player1, player2) VALUES
                ('{$this->status}', '{$this->phase}', '{$this->player1Name}', '{$this->player2Name}')
        ";
        $database->query($sql);
        $this->id = $database->insert_id;
    }

    private function createWordsInDb()
    {
        global $database;
        $values = [];
        foreach ($this->words as $cellNumber => &$word) {
            $word['game_id'] = $this->id;
            $word['cell_number'] = $cellNumber + 1;
            $values[] = "($this->id, {$word['cell_number']}, '{$word['word']}', '{$word['type_for_player1']}', '{$word['type_for_player2']}')";
        }
        $values = implode(', ', $values);

        $sql = "INSERT INTO game_words (game_id, cell_number, word, type_for_player1, type_for_player2) VALUES {$values}";
        $database->query($sql);
    }

    /**
     * Отдаёт инфу в нужном для фронтенда формате
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'turnsCount' => $this->turnsCount,
            'player1Name' => $this->player1Name,
            'player2Name' => $this->player2Name,
            'phase' => $this->phase,
            'status' => $this->status,
            'words' => []// todo
        ];
    }
}