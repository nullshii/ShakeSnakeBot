<?php

namespace App\Bot\Commands\Vote;


use App\Enums\Vote;

class VoteLeft extends VoteBase
{
    protected string $name = "vote_left";
    protected string $description = "Vote for left movement in next round";
    protected Vote $vote = Vote::LEFT;
}
