<?php

namespace App\Console\Commands;

use App\Enums\Cell;
use App\Enums\Vote;
use App\Services\GameService;
use App\Vector2;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestGameCommand extends Command
{
    protected $signature = "game:test";
    protected $description = "Test game";

    public function handle(GameService $game): void
    {
        $game->initEmpty();

        while (true) {
            Log::info($game->export());

            $vote = Vote::from(
                $this->anticipate('Choose direction', array_map(fn($case) => $case->value, Vote::cases()))
            );

            $game->nextVote($vote);
        }
    }
}
