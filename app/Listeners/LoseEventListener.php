<?php

namespace App\Listeners;

use App\Events\LoseEvent;
use App\Services\GameService;

readonly class LoseEventListener
{
    public function __construct(private GameService $gameService)
    {
    }

    public function handle(LoseEvent $event): void
    {
        $this->gameService->lose();
    }
}
