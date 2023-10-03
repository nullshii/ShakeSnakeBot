<?php

namespace App\Bot\Commands\Vote;

use App\Enums\Vote;

class VoteUp extends VoteBase
{
    protected string $name = "vote_up";
    protected string $description = "Vote for up movement in next round";
    protected Vote $vote = Vote::UP;
}
