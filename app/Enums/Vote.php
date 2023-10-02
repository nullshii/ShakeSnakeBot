<?php

namespace App\Enums;

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
}
