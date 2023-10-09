<?php

namespace App\Listeners;

use App\Events\EatEvent;
use App\Services\GameService;
use Illuminate\Support\Facades\Log;

readonly class EatEventListener
{
    public function __construct(private GameService $gameService)
    {
    }

    public function handle(EatEvent $event): void
    {
        $this->gameService->onEat();
    }
}
