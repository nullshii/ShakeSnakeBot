<?php

namespace App\Services;

use App\Enums\Cell;
use App\Enums\Vote;
use Exception;

class GameService
{
    private array $cells;

    public function __construct()
    {
        $size = 10;
        $this->cells = array_fill(0, $size, array_fill(0, $size, Cell::EMPTY));
    }

    public function initEmpty(): void
    {
        $baseGame = "🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥\n🟥🟫🟫🟫🟫🟫🟫🟫🟫🟥\n🟥🟫🟫🟫🟫🟫🟫🟫🟫🟥\n🟥🟫🟫🟫🟫🟫🟫🔼🟫🟥\n🟥🟫🟫🟫🟫🟫🟫⬆🟫🟥\n🟥🟫🟫🟫🟫🟫🟫🟫🟫🟥\n🟥🟫🟫🟫🟫🟫🟫🟫🟫🟥\n🟥🟫🟫🟫🟥🟫🟫🟫🟫🟥\n🟥🟫🟫🟫🟫🟫🟫🟫🟫🟥\n🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥\n";

        $this->import($baseGame);
    }

    public function import(string $game): void
    {
        /** @var string[][] $rows */
        $rows = array_map(fn($row) => mb_str_split($row), explode("\n", $game));

        for ($y = 0; $y < count($rows); $y++) {
            for ($x = 0; $x < count($rows[$y]); $x++) {
                $this->cells[$y][$x] = Cell::from($rows[$y][$x]);
            }
        }
    }

    public function export(): string
    {
        $text = "\n";

        foreach ($this->cells as $row) {
            /** @var Cell $col */
            foreach ($row as $col) {
                $text .= $col->value;
            }
            $text .= "\n";
        }

        return $text;
    }

    public function nextVote(Vote $vote): void
    {
        if ($vote == Vote::UP) {
            throw new Exception('To be implemented');
        } elseif ($vote == Vote::DOWN) {
            throw new Exception('To be implemented');
        } elseif ($vote == Vote::LEFT) {
            throw new Exception('To be implemented');
        } elseif ($vote == Vote::RIGHT) {
            throw new Exception('To be implemented');
        }
    }

    private function getSnakePosition(): array
    {
        $pos = [];
        for ($y = 0; $y < count($this->cells); $y++) {
            for ($x = 0; $x < count($this->cells[$y]); $x++) {
                if (!in_array(
                    $this->cells[$y][$x],
                    [Cell::SNAKE_HEAD_UP, Cell::SNAKE_HEAD_DOWN, Cell::SNAKE_HEAD_LEFT, Cell::SNAKE_HEAD_RIGHT]
                )) continue;

                $pos[] = ['x' => $x, 'y' => $y];
            }
        }

        return $pos;
    }

    private function getOtherSide(int $x, int $y)
    {
        throw new Exception("Implement this");
    }
}
