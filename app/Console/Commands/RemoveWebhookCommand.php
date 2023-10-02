<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class RemoveWebhookCommand extends Command
{
    protected $signature = 'webhook:remove';
    protected $description = 'Remove telegram webhook';

    public function handle(): void
    {
        Telegram::bot()->removeWebhook();
    }
}
