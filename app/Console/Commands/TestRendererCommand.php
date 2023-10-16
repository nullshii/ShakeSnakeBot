<?php

namespace App\Console\Commands;

use App\Game\Renderer;
use App\Services\GameService;
use Illuminate\Console\Command;
use function Termwind\render;

class TestRendererCommand extends Command
{
    protected $signature = 'renderer:test';

    protected $description = 'test renderer';

    public function handle(GameService $gameService): void
    {
        $map = "■■■■■■■■■■
■□□□□□□□●■
■□□□□□□□□■
■□□□□□□□□■
■□□□□□□□□■
■□□□□□□◀△■
■□□□□□□□△■
■□□□□□□□△■
■□□□▽▷▷▷▷■
■■■■■■■■■■";

        $gameService->import($map);

        $renderer = new Renderer($gameService);
        $renderer->render()->writeImage(storage_path('app/game.png'));
    }
}
