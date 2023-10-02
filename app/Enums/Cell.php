<?php

namespace App\Enums;

enum Cell : int {
    case EMPTY = 0;
    case SNAKE_BODY = 1;
    case SNAKE_HEAD = 2;
    case WALL = 3;
    case APPLE = 4;

    public function Emoji(): string
    {
        return match ($this) {
            Cell::EMPTY => "🟫",
            Cell::SNAKE_BODY => "🟩",
            Cell::SNAKE_HEAD => "🟢",
            Cell::WALL => "🟥",
            Cell::APPLE => "🔴",
        };
    }
}
