<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'state',
        'vote_up',
        'vote_down',
        'vote_left',
        'vote_right',
        'is_over',
    ];
}
