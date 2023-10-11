<?php

namespace App\Bot\Commands;

use App\Services\UserService;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class Start extends Command
{
    protected string $name = 'start';
    protected string $description = 'Start bot';

    public function __construct()
    {
    }

    public function handle(): void
    {
        if (!$this->update->message) return;

        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $this->replyWithMessage([
            'text' => "
Welcome to the Shake Snake bot.
Here, you can play snake with other players.
To participate in this game, you must join the game channel and vote for the next move, which happens every minute.
The game channel is here: https://t.me/shake_snake_group.
",
        ]);
    }
}
