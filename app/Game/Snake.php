<?php

namespace App\Game;

use App\Enums\CellType;
use App\Enums\Direction;
use App\Events\EatEvent;
use App\Events\LoseEvent;
use Exception;
use Illuminate\Support\Facades\Log;

class Snake
{
    /** @var Vector2[] */
    private array $snake;

    public function __construct(private readonly Map $map)
    {
        $vector2 = $this->findSnakeHeadPosition();
        $this->snake[] = $vector2;

        while (true) {
            try {
                $vector2 = $this->map->getOpposite($vector2);
            } catch (Exception) {
                Log::error("Something went wrong when creating snake...");
                break;
            }

            $cell = $this->map->getCellAtPosition($vector2);

            if ($cell->isSnakeBody())
                $this->snake[] = $vector2;
            else
                break;
        }
    }

    private function findSnakeHeadPosition(): Vector2
    {
        /** @var int $headPosition */
        $headPosition = $this->map
            ->collect()
            ->filter(fn(Cell $cell) => $cell->isSnakeHead())
            ->keys()
            ->first();

        return new Vector2($headPosition % $this->map->size, (int)$headPosition / $this->map->size);
    }

    public function getHeadPosition(): Vector2
    {
        return $this->snake[0];
    }

    public function move(Direction $direction): void
    {
        $headPosition = $this->snake[0];
        $nextPosition = $headPosition->addAsNew($direction->asVector2());
        $nextCell = $this->map->getCellAtPosition($nextPosition);

        if ($nextCell->isObstacle()) {
            LoseEvent::dispatch();
            return;
        }

        if ($nextCell->getCellType() == CellType::APPLE) {
            EatEvent::dispatch();
        } else {
            $end = array_pop($this->snake);
            $this->map->getCellAtPosition($end)->setCellType(CellType::EMPTY);
        }

        $this->map->getCellAtPosition($headPosition)->convertToBody();

        $nextCell->setCellType($direction->asSnakeHead());
        array_unshift($this->snake, $nextPosition);
    }
}
