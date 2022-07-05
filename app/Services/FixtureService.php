<?php

namespace App\Services;

use App\Models\Team;
use App\Models\Game;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class FixtureService
{

    protected int $games = 0;

    protected int $teamCount = 0;

    protected array $matchups = [];

    protected array $oddMatchups = [];

    /**
     * @var array[]
     */
    protected array $schedule;

    public function __construct(public EloquentCollection $teams)
    {
        $this->teams = config('app.env') != 'dev' && config('app.env') != 'testing'
            ? $teams->shuffle() : $teams;

        $this->teamCount = $teams->count();

        if (!$this->isEvenNumberOfTeams()) {
            $this->addDayOff();
        }

        $this->games = floor($this->teamCount / 2);

        $this->initMatchups();
    }

    public static function generate(EloquentCollection $teams)
    {
        $games = new EloquentCollection();
        $fixture = new static($teams);
        $fixture->getFullFixture()->each(function ($week) use ($games) {
            foreach ($week as $game) {
                if (!is_null($game['home']->id) && !is_null($game['away']->id)) {
                    $games->push(
                        Game::create([
                            'home_team_id' => $game['home']->id,
                            'away_team_id' => $game['away']->id,
                            'week' => $game['week'],
                        ])
                    );
                }
            }
        });

        return $games;
    }

    public function getSchedule()
    {
        $this->schedule = [$this->matchups];


        for ($game = 1; $game < $this->teamCount - 1; $game++) {
            $this->schedule[] = ($game % 2 == 0)
                ? $this->getEvenRound()
                : $this->getOddRound();
        }


        return $this->schedule;
    }

    public function getFullFixture()
    {
        $this->getSchedule();
        $this->fixture = [];
        foreach ($this->schedule as $week => $matchups) {
            foreach ($matchups as $matchup) {
                $this->fixture[$week + 1][] = [
                    'week' => $week + 1,
                    'home' => $matchup['home'],
                    'away' => $matchup['away'],
                ];
            }
        }
        if (config('league.fixture.allowRevenges')) {
            foreach ($this->schedule as $matchups) {
                $this->fixture[] = [];
                foreach ($matchups as $revenge) {
                    $this->fixture[count($this->fixture)][] = [
                        'week' => count($this->fixture),
                        'home' => $revenge['away'],
                        'away' => $revenge['home'],
                    ];
                }
            }
        }

        return collect($this->fixture);
    }

    public function isEvenNumberOfTeams(): bool
    {
        return $this->teamCount % 2 == 0;
    }

    protected function addDayOff(): void
    {
        $this->teams->push(new Team(['name' => 'Day Off']));
        $this->teamCount++;
    }

    protected function initMatchups()
    {
        for ($i = 0; $i < $this->games; $i++) {
            $this->matchups[] = [
                'home' => $this->teams->shift(),
                'away' => $this->teams->shift(),
            ];
        }
    }

    protected function getOddRound(): array
    {
        for ($i = 0; $i < $this->games; $i++) {
            if ($i == 0) {
                $this->oddMatchups[$i] = [
                    'home' => $this->matchups[$i]['away'],
                    'away' => $this->matchups[$this->games - 1]['home'],
                ];
            } else {
                $this->oddMatchups[$i] = [
                    'home' => $this->matchups[$i]['away'],
                    'away' => $this->matchups[$i - 1]['home'],
                ];
            }
        }

        return $this->oddMatchups;
    }

    protected function getEvenRound(): array
    {
        for ($i = 0; $i < $this->games; $i++) {
            if ($i == 0) {
                $this->matchups[$i] = [
                    'home' => $this->oddMatchups[$i]['home'],
                    'away' => $this->oddMatchups[$i + 1]['home'],
                ];
            } elseif ($i == $this->games - 1) {
                $this->matchups[$i] = [
                    'home' => $this->oddMatchups[0]['away'],
                    'away' => $this->oddMatchups[$i]['away'],
                ];
            } else {
                $this->matchups[$i] = [
                    'home' => $this->oddMatchups[$i]['away'],
                    'away' => $this->oddMatchups[$i + 1]['home'],
                ];
            }
        }

        return $this->matchups;
    }

}
