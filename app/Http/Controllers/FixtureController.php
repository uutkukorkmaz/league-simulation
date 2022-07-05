<?php

namespace App\Http\Controllers;

use App\Repositories\GameRepository;
use App\Repositories\TeamRepository;

class FixtureController extends Controller
{

    public function __construct(protected GameRepository $gameRepository, protected TeamRepository $teamRepository)
    {
    }

    public function index()
    {
        return view('fixture', ['fixture' => $this->gameRepository->getAll()->groupBy('week')]);
    }

    public function reset()
    {
        $this->gameRepository->truncate();

        return redirect()->route('fixture.index');
    }

    public function generate()
    {
        $this->gameRepository->truncate();
        $this->gameRepository->generate($this->teamRepository);

        return redirect()->route('fixture.index');
    }

}
