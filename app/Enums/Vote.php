<?php

namespace App\Enums;

enum Vote : int {
    case EMPTY = 0;
    case UP = 1;
    case DOWN = 2;
    case LEFT = 3;
    case RIGHT = 4;
}
