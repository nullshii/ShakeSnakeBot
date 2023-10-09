<?php

namespace App\Enums;

use Exception;

enum Vote: string
{
    case EMPTY = 'empty';
    case UP = 'up';
    case DOWN = 'down';
    case LEFT = 'left';
    case RIGHT = 'right';

    public function emoji(): string
    {
        return match ($this) {
            self::EMPTY => "⏺️",
            self::UP => "🔼",
            self::DOWN => "🔽",
            self::LEFT => "◀️",
            self::RIGHT => "▶️",
        };
    }

    public function asDirection(): Direction
    {
        return match ($this) {
            self::EMPTY => throw new Exception("Direction can not be empty"),
            self::UP => Direction::UP,
            self::DOWN => Direction::DOWN,
            self::LEFT => Direction::LEFT,
            self::RIGHT => Direction::RIGHT,
        };
    }
}
