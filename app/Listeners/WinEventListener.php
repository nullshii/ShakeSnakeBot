<?php

namespace App\Listeners;

use App\Events\WinEvent;
use App\Services\GameService;

readonly class WinEventListener
{
    public function __construct(private GameService $gameService)
    {
    }

    public function handle(WinEvent $event): void
    {
        $this->gameService->win();
    }
}
