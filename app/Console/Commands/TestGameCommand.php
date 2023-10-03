<?php

namespace App\Console\Commands;

use App\Enums\Vote;
use App\Services\GameService;
use Illuminate\Console\Command;

class TestGameCommand extends Command
{
    protected $signature = "game:test";
    protected $description = "Test game";

    public function handle(GameService $game): void
    {
        $game->initEmpty();

        while (true) {
            $this->line($game->export());

            $vote = Vote::from(
                $this->anticipate('Choose direction', array_map(fn($case) => $case->value, Vote::cases()))
            );

            $game->nextVote($vote);
        }
    }
}
