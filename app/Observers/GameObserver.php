<?php

namespace App\Observers;

use App\Models\Game;

class GameObserver
{

    public function updated(Game $game)
    {
        if (!$game->relationLoaded('home') || !$game->relationLoaded('away')) {
            $game->load('home', 'away');
        }
        switch ($game->result) {
            case 'DRAW':
                $game->home->drawn += 1;
                $game->away->drawn += 1;
                break;
            case 'HOME_WIN':
                $game->home->won += 1;
                $game->away->lost += 1;
                break;
            case 'AWAY_WIN':
                $game->home->lost += 1;
                $game->away->won += 1;
                break;
        }
        $game->home->goals_for += $game->home_goals;
        $game->home->goals_against += $game->away_goals;
        $game->away->goals_for += $game->away_goals;
        $game->away->goals_against += $game->home_goals;
        $game->home->save();
        $game->away->save();
        cache()->flush();
    }

}
