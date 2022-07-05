<?php

namespace Tests\Feature\Repositories;

use Tests\TestCase;
use App\Models\Team;
use App\Repositories\TeamRepository;

class TeamRepositoryTest extends TestCase
{

    /**
     * @test
     * @covers \App\Repositories\TeamRepository::getAll
     */
    public function get_all_function_should_return_all_teams()
    {
        $repository = new TeamRepository();
        $this->assertEquals(Team::all(), $repository->getAll());
    }

    /**
     * @test
     * @covers \App\Repositories\TeamRepository::reset
     */
    public function reset_function_should_reset_all_teams()
    {
        $repository = new TeamRepository();
        $repository->reset();
        $this->assertEquals(0, Team::all()->sum('won'));
        $this->assertEquals(0, Team::all()->sum('lost'));
        $this->assertEquals(0, Team::all()->sum('drawn'));
        $this->assertEquals(0, Team::all()->sum('goals_for'));
        $this->assertEquals(0, Team::all()->sum('goals_against'));
    }

}
