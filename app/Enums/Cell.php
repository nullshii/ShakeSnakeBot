<?php

namespace App\Enums;

enum Cell : string {
    case EMPTY = "🟫";
    case SNAKE_BODY = "🟩";
    case SNAKE_HEAD = "🟢";
    case WALL = "🟥";
    case APPLE = "🔴";
}
