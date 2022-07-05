<?php

namespace Tests\Feature\Services;

use Tests\TestCase;
use App\Models\Team;
use App\Models\Game;
use App\Services\FixtureService;
use App\Repositories\GameRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FixtureServiceTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     * @covers \App\Services\FixtureService::__construct
     * @covers \App\Services\FixtureService::generate
     * @covers \App\Services\FixtureService::getFullFixture
     * @covers \App\Services\FixtureService::initMatchups
     * @covers \App\Services\FixtureService::getSchedule
     * @covers \App\Services\FixtureService::addDayOff
     * @covers \App\Services\FixtureService::getOddRound
     * @covers \App\Services\FixtureService::getEvenRound
     */
    public function generate_function_should_generate_fixtures()
    {
        //even
        app()->make(GameRepository::class)->truncate();
        $this->assertEquals(0, Game::count());
        FixtureService::generate(Team::factory(4)->create());
        $this->assertEquals(12, Game::count());

        //odd
        app()->make(GameRepository::class)->truncate();
        $this->assertEquals(0, Game::count());
        FixtureService::generate(Team::factory(5)->create());
        $this->assertEquals(20, Game::count());
    }

    /**
     * @test
     * @covers \App\Services\FixtureService::isEvenNumberOfTeams
     */
    public function is_even_number_of_teams_function_should_return_true_if_number_of_teams_is_even()
    {
        $service = new FixtureService(Team::factory(4)->create());
        $this->assertTrue($service->isEvenNumberOfTeams());
    }

}
