<?php

namespace App\Http\Controllers;

use App\Services\SimulationService;
use App\Repositories\GameRepository;
use App\Http\Requests\SimulateRequest;

class SimulationController extends Controller
{

    public function __construct(public SimulationService $simulation, public GameRepository $gameRepository)
    {
    }

    public function __invoke(SimulateRequest $request)
    {
        switch ($request->validated('week')) {
            default:
            case "next":
                $this->simulateNextWeek();
                break;
            case "all":
                $this->simulateAllWeeks();
                break;
        }

        return redirect()->back()->with('success', 'Simulated');
    }

    private function simulateNextWeek()
    {
        $this->simulation->simulateNextWeek($this->gameRepository);
    }

    private function simulateAllWeeks()
    {
        for ($w = 1; $w <= $this->simulation->getWeekCount(); $w++) {
            if ($w < $this->simulation->getNextWeek()) {
                continue;
            }
            $this->simulation->simulateNextWeek($this->gameRepository);
        }
    }


}
