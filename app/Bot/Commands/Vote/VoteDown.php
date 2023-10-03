<?php

namespace App\Bot\Commands\Vote;

use App\Enums\Vote;

class VoteDown extends VoteBase
{
    protected string $name = "vote_down";
    protected string $description = "Vote for down movement in next round";
    protected Vote $vote = Vote::DOWN;
}
