<?php

namespace App\Bot\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class Help extends Command
{
    protected string $name = 'help';
    protected string $pattern = '{is_dev}';
    protected string $description = 'Get help about bot.';

    public function handle(): void
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $isDev = $this->argument('is_dev');

        $text = '';
        $commands = $this->telegram->getCommandBus()->getCommands();

        foreach ($commands as $command) {
            $text .= sprintf('%s%s - %s' . PHP_EOL, $isDev ? '' : '/', $command->getName(), $command->getDescription());
        }

        $this->replyWithMessage([
            'text' => "Commands:" . PHP_EOL . $text
        ]);
    }
}
