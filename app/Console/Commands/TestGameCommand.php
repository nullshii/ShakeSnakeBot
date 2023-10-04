<?php

namespace App\Console\Commands;

use App\Enums\Cell;
use App\Enums\Vote;
use App\Services\GameService;
use App\Vector2;
use Illuminate\Console\Command;

class TestGameCommand extends Command
{
    protected $signature = "game:test";
    protected $description = "Test game";

    public function handle(GameService $game): void
    {
        $game->initEmpty();

        $this->line(var_export($game->snake, true));

        while (true) {
            $this->line($game->export());

            $vote = Vote::from(
                $this->anticipate('Choose direction', array_map(fn($case) => $case->value, Vote::cases()))
            );

            $game->nextVote($vote);
        }
    }
}
