<?php

namespace App\Services;

use App\Models\Game;
use App\Repositories\GameRepository;

class SimulationService
{

    public static function isLeagueScheduled(): bool
    {
        return Game::count() > 0;
    }

    public static function isLeagueFinished(): bool
    {
        return Game::count() > 0 && Game::where('is_played', false)->count() == 0;
    }

    public function simulateNextWeek(GameRepository $gameRepository)
    {
        $games = $gameRepository->getGamesByWeek($this->getNextWeek());
        $games->each(fn(Game $game) => $this->simulate($game));
    }

    public function simulate(Game $game)
    {
        $this->loadRelations($game);
        $home = $game->home;
        $away = $game->away;
        $scores = (new PredictionService())->predictMatchResult($home, $away);
        $game->update([
            'home_goals' => $scores['home_goals'],
            'away_goals' => $scores['away_goals'],
            'is_played' => true,
        ]);
    }

    public function getNextWeek(): ?int
    {
        return Game::where('is_played', false)
            ->orderBy('week')
            ->first()?->week;
    }

    public function getWeekCount(): ?int
    {
        return Game::max('week');
    }

    protected function loadRelations(Game $game)
    {
        if (!$game->relationLoaded('home') || !$game->relationLoaded('away')) {
            $game->load('home', 'away');
        }
    }

}
