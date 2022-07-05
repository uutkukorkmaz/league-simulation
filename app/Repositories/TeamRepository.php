<?php

namespace App\Repositories;

use App\Models\Team;

class TeamRepository
{

    protected Team $team;

    public static function reset()
    {
        Team::all()->each(fn(Team $team) => $team->update([
            'won' => 0,
            'lost' => 0,
            'drawn' => 0,
            'goals_for' => 0,
            'goals_against' => 0,
        ]));
        cache()->forget('teams');
    }

    public function getAll()
    {
        return cache()->remember('teams', 5000, fn() => Team::all());
    }


}
