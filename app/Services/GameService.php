<?php

namespace App\Services;

use App\Enums\Cell;
use App\Enums\Vote;
use App\Vector2;
use Exception;
use Illuminate\Support\Facades\Log;

class GameService
{
    public array $cells;

    /** @var Vector2[] */
    public array $snake;

    public function __construct()
    {
        $size = 10;
        $this->cells = array_fill(0, $size, array_fill(0, $size, Cell::EMPTY));
    }

    public function initEmpty(): void
    {
        $baseGame = "🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥\n🟥🟫🟫🟫🟫🟫🟫🟫🟫🟥\n🟥🟫🟫🟫🟫🟫🟫🟫🟫🟥\n🟥🟫🟫🟫🟫🟫🟫🔼🟫🟥\n🟥🟫🟫🟫🟫🟫🟫⬆🟫🟥\n🟥🟫🟫🟫🟫➡➡➡🟫🟥\n🟥🟫🟫🟫🟫🟫🟫🟫🟫🟥\n🟥🟫🟫🟫🟫🟫🟫🟫🟫🟥\n🟥🟫🟫🟫🟫🟫🟫🟫🟫🟥\n🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥\n";

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

        $snakeHeadPosition = $this->getSnakeHeadPosition();
        $otherSide = $snakeHeadPosition->clone();
        $this->snake[] = $snakeHeadPosition;

        while (true) {
            $otherSide = $this->getOppositeSide($otherSide);

            if (in_array($this->getCell($otherSide), [Cell::EMPTY, Cell::WALL]))
                break;

            $this->snake[] = $otherSide;
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
        $endOfSnake = array_pop($this->snake);
        $this->setCell($endOfSnake, Cell::EMPTY);

        $headPosition = $this->getSnakeHeadPosition();
        $nextPosition = $headPosition->addAsNew($vote->toDirection());
        $this->convertHeadToBody($headPosition);
        $this->setCell($nextPosition, Cell::headFromVote($vote));
        array_unshift($this->snake, $nextPosition);
    }

    private function getSnakeHeadPosition(): Vector2
    {
        $pos = Vector2::zero();

        for ($y = 0; $y < count($this->cells); $y++) {
            for ($x = 0; $x < count($this->cells[$y]); $x++) {
                if (!in_array(
                    $this->cells[$y][$x],
                    [Cell::SNAKE_HEAD_UP, Cell::SNAKE_HEAD_DOWN, Cell::SNAKE_HEAD_LEFT, Cell::SNAKE_HEAD_RIGHT]
                )) continue;

                $pos->x = $x;
                $pos->y = $y;
            }
        }

        return $pos;
    }

    private function getOppositeSide(Vector2 $vector2): Vector2
    {
        $pos = $vector2->clone();

        $cell = $this->getCell($vector2);
        Log::info('Start from: ' . $cell->value);

        $pos->add(
            match ($cell) {
                Cell::SNAKE_HEAD_UP, Cell::SNAKE_BODY_UP => Vector2::down(),
                Cell::SNAKE_HEAD_DOWN, Cell::SNAKE_BODY_DOWN => Vector2::up(),
                Cell::SNAKE_HEAD_LEFT, Cell::SNAKE_BODY_LEFT => Vector2::right(),
                Cell::SNAKE_HEAD_RIGHT, Cell::SNAKE_BODY_RIGHT => Vector2::left(),
                default => Vector2::zero()
            }
        );

        Log::info('Find that: ' . $this->getCell($pos)->value);

        return $pos;
    }

    public function getCell(Vector2 $vector2): Cell
    {
        return $this->cells[$vector2->y][$vector2->x];
    }

    private function setCell(Vector2 $vector2, Cell $cell): void
    {
        $this->cells[$vector2->y][$vector2->x] = $cell;
    }

    private function isCellEmpty(Vector2 $collisionPosition): bool
    {
        return $this->getCell($collisionPosition) != Cell::EMPTY;
    }

    /**
     * @throws Exception
     */
    private function convertHeadToBody(Vector2 $headPosition): void
    {
        $cell = $this->getCell($headPosition);

        if (!in_array($cell, [
            Cell::SNAKE_HEAD_UP, Cell::SNAKE_HEAD_DOWN,
            Cell::SNAKE_HEAD_LEFT, Cell::SNAKE_HEAD_RIGHT
        ])) throw new Exception("{$headPosition->toString()} is not head position");

        $convertedCell = match ($cell) {
            Cell::SNAKE_HEAD_UP => Cell::SNAKE_BODY_UP,
            Cell::SNAKE_HEAD_DOWN => Cell::SNAKE_BODY_DOWN,
            Cell::SNAKE_HEAD_LEFT => Cell::SNAKE_BODY_LEFT,
            Cell::SNAKE_HEAD_RIGHT => Cell::SNAKE_BODY_RIGHT,
        };

        $this->setCell($headPosition, $convertedCell);
    }
}
