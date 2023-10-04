<?php

namespace App\Enums;

use App\Vector2;

enum Vote : string {
    case EMPTY = '';
    case UP = 'up';
    case DOWN = 'down';
    case LEFT = 'left';
    case RIGHT = 'right';

    public function emoji() : string {
        return match($this){
            self::EMPTY => "⏺️",
            self::UP => "🔼",
            self::DOWN => "🔽",
            self::LEFT => "◀️",
            self::RIGHT => "▶️",
        };
    }

    public function toDirection(): Vector2 {
        return match ($this){
            self::EMPTY => Vector2::zero(),
            self::UP => Vector2::up(),
            self::DOWN => Vector2::down(),
            self::LEFT => Vector2::left(),
            self::RIGHT => Vector2::right(),
        };
    }
}
