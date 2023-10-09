<?php

namespace App\Console\Commands;

use App\Enums\Direction;
use App\Services\GameService;
use Illuminate\Console\Command;

class TestGameCommand extends Command
{
    protected $signature = "game:test";
    protected $description = "Test game";

    public function handle(GameService $game): void
    {
        while (true) {
            $this->line($game->export());

            $name = $this->anticipate(
                'Choose direction',
                array_map(fn(Direction $case) => mb_strtolower($case->name), Direction::cases())
            );

            $direction = collect(Direction::cases())
                ->first(fn(Direction $case) => $case->name == mb_strtoupper($name));

            $game->move($direction);
        }
    }
}
