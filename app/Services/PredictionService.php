<?php

namespace App\Services;

use App\Models\Team;

class PredictionService
{

    public static function predictChampion()
    {
        $teams = cache()->remember('teams', 5000, fn() => Team::all());

        $totalStrength = $teams->sum('strength');

        return $teams->each(function ($team) use ($totalStrength) {
            $team->championship_chance = max(($team->strength / max($totalStrength - $team->strength, 1)) * 100, 0);
        })->sortByDesc('championship_chance');
    }

    public function predictMatchResult(Team $home, Team $away)
    {
        [$homeDominance, $awayDominance] = $this->getDominance($home->strength, $away->strength);
        $homeScore = intval(ceil((rand(0, rand(1, 3))) + (($homeDominance - $awayDominance) / 100)));
        $awayScore = intval(ceil((rand(0, rand(1, 3))) + (($awayDominance - $homeDominance) / 100)));

        return [
            'home_goals' => max($homeScore, 0),
            'away_goals' => max($awayScore, 0),
        ];
    }

    public function getDominance(float $homeStrength, float $awayStrength): array
    {
        $total = max($homeStrength + $awayStrength, 1);

        return [
            $homeStrength / $total,
            ($awayStrength / $total) - !rand(0, 3) ? config('league.morale_factor') : 0,
        ];
    }

}
