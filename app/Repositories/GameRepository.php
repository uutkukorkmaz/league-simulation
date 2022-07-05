<?php

namespace App\Repositories;

use App\Models\Game;
use App\Services\FixtureService;
use Illuminate\Database\Eloquent\Collection;

class GameRepository
{

    public function getAll()
    {
        return cache()->remember('all-games', 5000, fn() => Game::with('home', 'away')->orderBy('week')->get());
    }

    public function comingUp()
    {
        return Game::with('home', 'away')
            ->where('is_played', false)
            ->orderBy('week')
            ->first();
    }

    public function truncate()
    {
        cache()->forget('all-games');
        Game::whereNotNull('id')->delete();
        TeamRepository::reset();
    }


    public function generate(TeamRepository $teamRepository)
    {
        cache()->forget('all-games');
        FixtureService::generate($teamRepository->getAll());
        TeamRepository::reset();
    }

    public function getGamesByWeek($week): Collection|array
    {
        return Game::with('home', 'away')->where('week', $week)->get();
    }

}
