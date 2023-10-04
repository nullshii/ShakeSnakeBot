<?php

namespace App\Enums;

use App\Vector2;
use Exception;

enum Cell: string
{
    case EMPTY = "🟫";
    case SNAKE_BODY_UP = "⬆";
    case SNAKE_BODY_DOWN = "⬇";
    case SNAKE_BODY_LEFT = "⬅";
    case SNAKE_BODY_RIGHT = "➡";
    case SNAKE_HEAD_UP = "🔼";
    case SNAKE_HEAD_DOWN = "🔽";
    case SNAKE_HEAD_LEFT = "◀";
    case SNAKE_HEAD_RIGHT = "▶";
    case WALL = "🟥";
    case APPLE = "🔴";

    public static function headFromVote(Vote $vote): Cell
    {
        return match ($vote) {
            Vote::EMPTY => throw new Exception('AAAAAAAAAAAAAA'),
            Vote::UP => Cell::SNAKE_HEAD_UP,
            Vote::DOWN => Cell::SNAKE_HEAD_DOWN,
            Vote::LEFT => Cell::SNAKE_HEAD_LEFT,
            Vote::RIGHT => Cell::SNAKE_HEAD_RIGHT,
        };
    }
}
