<?php

namespace App\Game;

use App\Enums\CellType;
use Exception;

class Cell
{
    public function __construct(private CellType $cellType = CellType::EMPTY)
    {
    }

    public function getCellType(): CellType
    {
        return $this->cellType;
    }

    public function setCellType(CellType $cellType): void
    {
        $this->cellType = $cellType;
    }

    public function convertToHead(): void
    {
        if (!$this->isSnakeBody()) return;

        $cellType = match ($this->getCellType()) {
            CellType::SNAKE_BODY_UP => CellType::SNAKE_HEAD_UP,
            CellType::SNAKE_BODY_DOWN => CellType::SNAKE_HEAD_DOWN,
            CellType::SNAKE_BODY_LEFT => CellType::SNAKE_HEAD_LEFT,
            CellType::SNAKE_BODY_RIGHT => CellType::SNAKE_HEAD_RIGHT,
            default => $this->getCellType(),
        };

        $this->setCellType($cellType);
    }

    public function convertToBody(): void
    {
        if (!$this->isSnakeHead()) return;

        $cellType = match ($this->getCellType()) {
            CellType::SNAKE_HEAD_UP => CellType::SNAKE_BODY_UP,
            CellType::SNAKE_HEAD_DOWN => CellType::SNAKE_BODY_DOWN,
            CellType::SNAKE_HEAD_LEFT => CellType::SNAKE_BODY_LEFT,
            CellType::SNAKE_HEAD_RIGHT => CellType::SNAKE_BODY_RIGHT,
            default => $this->getCellType(),
        };

        $this->setCellType($cellType);
    }

    public function isSnakeHead(): bool
    {
        return in_array($this->getCellType(), [
            CellType::SNAKE_HEAD_UP, CellType::SNAKE_HEAD_DOWN,
            CellType::SNAKE_HEAD_LEFT, CellType::SNAKE_HEAD_RIGHT
        ]);
    }

    public function isSnakeBody(): bool
    {
        return in_array($this->cellType, [
            CellType::SNAKE_BODY_UP, CellType::SNAKE_BODY_DOWN,
            CellType::SNAKE_BODY_LEFT, CellType::SNAKE_BODY_RIGHT
        ]);
    }

    public function isDirectionUp(): bool
    {
        return in_array($this->cellType, [
            CellType::SNAKE_HEAD_UP,
            CellType::SNAKE_BODY_UP,
        ]);
    }

    public function isDirectionDown(): bool
    {
        return in_array($this->cellType, [
            CellType::SNAKE_HEAD_DOWN,
            CellType::SNAKE_BODY_DOWN,
        ]);
    }

    public function isDirectionLeft(): bool
    {
        return in_array($this->cellType, [
            CellType::SNAKE_HEAD_LEFT,
            CellType::SNAKE_BODY_LEFT,
        ]);
    }

    public function isDirectionRight(): bool
    {
        return in_array($this->cellType, [
            CellType::SNAKE_HEAD_RIGHT,
            CellType::SNAKE_BODY_RIGHT,
        ]);
    }

    public function isObstacle(): bool
    {
        return in_array($this->cellType, [
            CellType::SNAKE_HEAD_UP,
            CellType::SNAKE_HEAD_DOWN,
            CellType::SNAKE_HEAD_LEFT,
            CellType::SNAKE_HEAD_RIGHT,
            CellType::SNAKE_BODY_UP,
            CellType::SNAKE_BODY_DOWN,
            CellType::SNAKE_BODY_LEFT,
            CellType::SNAKE_BODY_RIGHT,
            CellType::WALL,
        ]);
    }
}
