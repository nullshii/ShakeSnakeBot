<?php

namespace App\Enums;

use App\Game\Vector2;

enum Direction: string
{
    case UP = "🔼";
    case DOWN = "🔽";
    case LEFT = "◀";
    case RIGHT = "▶";

    public function asVector2(): Vector2
    {
        return match ($this)
        {
            self::UP => Vector2::up(),
            self::DOWN => Vector2::down(),
            self::LEFT => Vector2::left(),
            self::RIGHT => Vector2::right(),
        };
    }

    public function asSnakeHead(): CellType
    {
        return match ($this)
        {
            self::UP => CellType::SNAKE_HEAD_UP,
            self::DOWN => CellType::SNAKE_HEAD_DOWN,
            self::LEFT => CellType::SNAKE_HEAD_LEFT,
            self::RIGHT => CellType::SNAKE_HEAD_RIGHT,
        };
    }
}
