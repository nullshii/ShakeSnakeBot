<?php

namespace App\Services;

use App\Enums\Cell;

class GameService
{
    private array $cells;

    public function __construct()
    {
        $size = 10;
        $this->cells = array_fill(0, $size, array_fill(0, $size, Cell::EMPTY));
    }

    public function InitEmpty(): void
    {
        $baseGame = "🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥\n🟥🟫🟫🟫🟫🟫🟫🟫🟫🟥\n🟥🟫🟫🟫🟫🟫🟫🟫🟫🟥\n🟥🟫🟫🟫🟫🟫🟫🟫🟫🟥\n🟥🟫🟫🟫🟫🟫🟫🟫🟫🟥\n🟥🟫🟫🟫🟫🟫🟫🟫🟫🟥\n🟥🟫🟫🟫🟫🟫🟫🟫🟫🟥\n🟥🟫🟫🟫🟫🟫🟫🟫🟫🟥\n🟥🟫🟫🟫🟫🟫🟫🟫🟫🟥\n🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥\n";

        $this->import($baseGame);
    }

    public function import(string $game): void
    {
        /** @var string[][] $rows */
        $rows = array_map(fn ($row) => mb_str_split($row), explode("\n", $game));

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
}
