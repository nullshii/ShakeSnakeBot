<?php

namespace App\Bot\Commands\Vote;

use App\Enums\Vote;

class VoteRight extends VoteBase
{
    protected string $name = "vote_right";
    protected string $description = "Vote for right movement in next round";
    protected Vote $vote = Vote::RIGHT;
}
