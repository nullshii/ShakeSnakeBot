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

        for ($y = 0; $y < count($this->cells); $y++) {
            for ($x = 0; $x < count($this->cells[$y]); $x++) {
                if (
                    $y <> 0 && $y <> count($this->cells) - 1 &&
                    $x <> 0 && $x <> count($this->cells[$y]) - 1
                ) continue;

                $this->cells[$y][$x] = Cell::WALL;
            }
        }
    }

    public function Export(): string
    {
        $text = "\n";

        foreach ($this->cells as $row) {
            /** @var Cell $col */
            foreach ($row as $col) {
                $text .= $col->Emoji();
            }
            $text .= "\n";
        }

        return $text;
    }
}
