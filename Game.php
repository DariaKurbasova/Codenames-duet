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

    private $wordsToShow = [];
    private $agentsFound = 0;

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

                $game->getWordsFromDb();
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
        $this->processWordsInfo();

        return [
            'id' => $this->id,
            'player1Name' => $this->player1Name,
            'player2Name' => $this->player2Name,
            'status' => $this->status,
            'phase' => $this->phase,
            'turnsCount' => $this->turnsCount,
            'agentsFound' => $this->agentsFound,
            'words' => $this->wordsToShow
        ];
    }

    public function getWordsFromDb()
    {
        global $database;
        if ($this->id) {
            $sql = "SELECT * FROM game_words WHERE game_id = {$this->id} ORDER BY cell_number";
            $words = $database->query($sql);
            $this->words = [];
            while ($word = $words->fetch_assoc()) {
                $this->words[] = $word;
            }
        }
    }

    /**
     * Смотрим слова
     */
    private function processWordsInfo()
    {
        // Здесь основная логика, связанная с показом слов юзеру
        $wordsToShow = [];
        $isFirstPlayer = ($this->yourPlayerIndex == 1);
        $agentsFound = 0;

        foreach ($this->words as $word) {
            if ($isFirstPlayer) {
                $type_me = $word['type_for_player1'];
                $type_partner = $word['type_for_player2'];
                $guessed_me = $word['guessed_by_player1'];
                $guessed_partner = $word['guessed_by_player2'];
            } else {
                $type_me = $word['type_for_player2'];
                $type_partner = $word['type_for_player1'];
                $guessed_me = $word['guessed_by_player2'];
                $guessed_partner = $word['guessed_by_player1'];
            }

            // Если вы не пытались угадать это слово, то его тип для вас неизвестен
            if (!$guessed_me) {
                $type_me = 'unknown';
            }

            $wordsToShow[] = [
                'cell_number' => $word['cell_number'],
                'word' => $word['word'],
                'type_me' => $type_me,
                'type_partner' => $type_partner,
                'guessed_me' => (bool)$guessed_me,
                'guessed_partner' => (bool)$guessed_partner,
            ];

            // Считаем количество угаданных слов
            if (($type_me == 'agent' && $guessed_me) || ($type_partner == 'agent' || $guessed_partner)) {
                $agentsFound++;
            }
        }

        $this->wordsToShow = $wordsToShow;
        $this->agentsFound = $agentsFound;
    }
}