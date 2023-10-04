<?php

namespace App;

class Vector2
{
    public function __construct(public int $x, public int $y)
    {
    }

    public static function zero(): Vector2
    {
        return new Vector2(0, 0);
    }

    public static function right(): Vector2
    {
        return new Vector2(1, 0);
    }

    public static function left(): Vector2
    {
        return new Vector2(-1, 0);
    }

    public static function up(): Vector2
    {
        return new Vector2(0, -1);
    }

    public static function down(): Vector2
    {
        return new Vector2(0, 1);
    }

    public function toString(): string
    {
        return "($this->x, $this->y)";
    }

    public function clone(): Vector2
    {
        return new Vector2($this->x, $this->y);
    }

    public function add(Vector2 $vector2): void
    {
        $this->x += $vector2->x;
        $this->y += $vector2->y;
    }

    public function addAsNew(Vector2 $vector2): Vector2
    {
        return new Vector2(
            $this->x + $vector2->x,
            $this->y + $vector2->y
        );
    }
}
