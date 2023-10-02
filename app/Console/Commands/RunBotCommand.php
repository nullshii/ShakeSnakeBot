<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class RunBotCommand extends Command
{
    protected $signature = "bot:run";
    protected $description = "Run telegram bot";

    public function handle(): void
    {
        while (true) {
            Telegram::commandsHandler();
            usleep(100000);
        }
    }
}
