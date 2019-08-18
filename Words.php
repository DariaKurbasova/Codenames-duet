<?php

class Words {

    public $words_array = [];
    public $words_for_game = [];

    private function getWordsArray() {
        $this->words_array = explode(', ', file_get_contents('words.txt'));
    }

    public function generate() {
        $this->getWordsArray();
        shuffle($this->words_array);
        array_splice($this->words_array, 25);

        foreach ($this->words_array as $i => $element) {
            if ($i < 5) {
                $this->words_for_game[] = [
                    'word'  => $element,
                    'type_for_player1' => 'agent',
                    'type_for_player2' => 'neutral'
                ];
            } elseif ($i < 10) {
                $this->words_for_game[$element] = [
                    'word'  => $element,
                    'type_for_player1' => 'neutral',
                    'type_for_player2' => 'agent'
                ];
            } elseif ($i == 10) {
                $this->words_for_game[$element] = [
                    'word'  => $element,
                    'type_for_player1' => 'agent',
                    'type_for_player2' => 'killer'
                ];
            } elseif ($i == 11) {
                $this->words_for_game[$element] = [
                    'word'  => $element,
                    'type_for_player1' => 'killer',
                    'type_for_player2' => 'agent'
                ];
            } elseif ($i == 12) {
                $this->words_for_game[$element] = [
                    'word'  => $element,
                    'type_for_player1' => 'killer',
                    'type_for_player2' => 'killer'
                ];
            } elseif ($i < 16) {
                $this->words_for_game[$element] = [
                    'word'  => $element,
                    'type_for_player1' => 'agent',
                    'type_for_player2' => 'agent'
                ];
            } elseif ($i < 23) {
                $this->words_for_game[$element] = [
                    'word'  => $element,
                    'type_for_player1' => 'neutral',
                    'type_for_player2' => 'neutral'
                ];
            } elseif ($i == 23) {
                $this->words_for_game[$element] = [
                    'word'  => $element,
                    'type_for_player1' => 'killer',
                    'type_for_player2' => 'neutral'
                ];
            } elseif ($i == 24) {
                $this->words_for_game[$element] = [
                    'word'  => $element,
                    'type_for_player1' => 'neutral',
                    'type_for_player2' => 'killer'
                ];
            }
        }
        shuffle($this->words_for_game);
        return($this->words_for_game);
    }
}