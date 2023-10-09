<?php

namespace App\Game;

use App\Enums\CellType;
use App\Events\WinEvent;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class Map
{
    /** @var Cell[] */
    private array $cells;

    public function __construct(public readonly int $size)
    {
        for ($i = 0; $i < $this->size * $this->size; $i++) {
            $this->cells[] = new Cell();
        }

        // Create empty map with snake and apple
        for ($y = 0; $y < $this->size; $y++) {
            for ($x = 0; $x < $this->size; $x++) {
                if ($x == 0 || $x == $this->size - 1 || $y == 0 || $y == $this->size - 1) {
                    $this->getCellAtPosition($x, $y)->setCellType(CellType::WALL);
                }
            }
        }

        $this->getCellAtPosition(5, 5)->setCellType(CellType::SNAKE_HEAD_UP);
        $this->getCellAtPosition(5, 6)->setCellType(CellType::SNAKE_BODY_UP);

        $this->createApple();
    }

    public function collect(): Collection
    {
        return collect($this->cells);
    }

    public function getCellAtPosition(Vector2|int $x, int $y = 0): Cell
    {
        if ($x instanceof Vector2) // No polymorphism
            return $this->getCellAtPosition($x->x, $x->y);

        return $this->cells[$y * $this->size + $x];
    }

    public function toString(): string
    {
        $str = "";

        for ($y = 0; $y < $this->size; $y++) {
            for ($x = 0; $x < $this->size; $x++) {
                $str .= $this->getCellAtPosition($x, $y)->getCellType()->value;
            }
            $str .= "\n";
        }

        return rtrim($str);
    }

    public static function fromString(string $str): Map
    {
        $rows = explode("\n", $str);

        $map = new Map(count($rows));

        for ($y = 0; $y < $map->size; $y++) {
            $cols = mb_str_split($rows[$y]);
            for ($x = 0; $x < $map->size; $x++) {
                $map->getCellAtPosition($x, $y)->setCellType(CellType::from($cols[$x]));
            }
        }

        return $map;
    }

    public function createApple(): void
    {
        $emptyCellKeys = collect($this->cells)
            ->filter(fn(Cell $cell) => $cell->getCellType() == CellType::EMPTY)
            ->keys();

        if ($emptyCellKeys->count() < 1) {
            WinEvent::dispatch();
            return;
        }

        $emptyCellKey = $emptyCellKeys->random();

        $this->cells[$emptyCellKey]->setCellType(CellType::APPLE);
    }

    /**
     * @throws Exception
     */
    public function getOpposite(Vector2 $position): Vector2
    {
        $cell = $this->getCellAtPosition($position);

        if ($cell->isDirectionUp()) {
            return $position->addAsNew(Vector2::down());
        }

        if ($cell->isDirectionDown()) {
            return $position->addAsNew(Vector2::up());
        }

        if ($cell->isDirectionLeft()) {
            return $position->addAsNew(Vector2::right());
        }

        if ($cell->isDirectionRight()) {
            return $position->addAsNew(Vector2::left());
        }

        throw new Exception("Cell type is not snake part");
    }
}
