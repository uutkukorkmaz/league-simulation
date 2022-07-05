<?php

namespace App\Http\Controllers;

use App\Services\SimulationService;
use App\Services\PredictionService;
use App\Repositories\TeamRepository;
use App\Repositories\GameRepository;

class LeagueController extends Controller
{

    public function __construct(public TeamRepository $teamRepository, public GameRepository $gameRepository)
    {
    }

    public function __invoke(SimulationService $simulation)
    {
        $nextWeek = $simulation->getNextWeek() ??
            (SimulationService::isLeagueFinished() ? $simulation->getWeekCount() + 1 : 0);
        $previousWeek = max($nextWeek - 1, 0);

        return view(
            'standings',
            [
                'teams' => $this->teamRepository->getAll(),

                'comingUp' => $this->gameRepository
                    ->getGamesByWeek($nextWeek)
                    ?->groupBy('week'),

                'previous' => $this->gameRepository
                    ->getGamesByWeek($previousWeek)
                    ?->groupBy('week'),

                'predictions' => SimulationService::isLeagueScheduled()
                    ? PredictionService::predictChampion()->sortByDesc('championship_chance') : null,
            ]
        );
    }


}
