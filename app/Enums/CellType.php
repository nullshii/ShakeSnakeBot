<?php

namespace App\Enums;

enum CellType: string
{
    case EMPTY = "□";
    case WALL = "■";
    case APPLE = "●";
    case SNAKE_BODY_UP = "△";
    case SNAKE_BODY_DOWN = "▽";
    case SNAKE_BODY_LEFT = "◁";
    case SNAKE_BODY_RIGHT = "▷";
    case SNAKE_HEAD_UP = "▲";
    case SNAKE_HEAD_DOWN = "▼";
    case SNAKE_HEAD_LEFT = "◀";
    case SNAKE_HEAD_RIGHT = "▶";
}
