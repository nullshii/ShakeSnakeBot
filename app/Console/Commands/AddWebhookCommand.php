<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class AddWebhookCommand extends Command
{
    protected $signature = "webhook:add";
    protected $description = "Add telegram webhook";

    public function handle(): void
    {
        Telegram::setWebhook(['url' => 'https://hook.nullshii.dev/07byry0vq709ewo7tyrq978tr6732']);
    }
}
