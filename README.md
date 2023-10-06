# Snake bot
### Snake bot for telegram written in php :)

## Summary

This is the implementation of the snake game within the Telegram bot.
Every minute, it collects votes from all users and submits the next move based on those votes.
That bot is available [here](https://t.me/shake_snake_bot).

## Screenshots

![alt text](preview.png)

## How to run

- Install [requirements for Laravel](https://laravel.com/docs/10.x/deployment#server-requirements)
- Install [composer](https://getcomposer.org/download/)
- Install dependencies

```shell
composer install
```

- Copy `.env.example` as `.env` file
- Add your token to `TELEGRAM_BOT_TOKEN` key
- Configure database keys (`DB_CONNECTION`, `DB_HOST`, 
`DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`)
- Migrate database with command below:

```shell
php artisan migrate
```

- Run bot with command below:

```shell
php artisan bot:run 
```
---
You also need the task scheduler to run `php artisan schedule:run` every minute.
For example, you can use `cron`:

```shell
crontab -e
```

and add to schedule

```
* * * * * cd path-to/shake-snake-bot && php artisan schedule:run >> /dev/null 2>&1
```

**⚠️ DON'T FORGET TO CHANGE THE PROJECT PATH TO YOUR OWN ⚠️**
