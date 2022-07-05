<?php

namespace Tests\Feature\Repositories;

use Tests\TestCase;
use App\Models\Team;
use App\Models\Game;
use App\Services\FixtureService;
use App\Services\SimulationService;
use App\Repositories\TeamRepository;
use App\Repositories\GameRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GameRepositoryTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     * @covers \App\Repositories\GameRepository::getAll
     */
    public function get_all_function_should_return_all_games()
    {
        FixtureService::generate(Team::factory(2)->create());
        $repository = new GameRepository();
        $this->assertEquals(Game::with('home', 'away')->orderBy('week')->get(), $repository->getAll());
    }

    /**
     * @test
     * @covers \App\Repositories\GameRepository::getGamesByWeek
     */
    public function get_games_by_week_function_should_return_games_by_week()
    {
        FixtureService::generate(Team::factory(2)->create());
        $repository = new GameRepository();
        $this->assertEquals(Game::with('home', 'away')->where('week', 1)->get(), $repository->getGamesByWeek(1));
    }

    /**
     * @test
     * @covers \App\Repositories\GameRepository::comingUp
     */
    public function coming_up_function_should_return_games_in_next_week()
    {
        $repository = new GameRepository();
        $repository->truncate();

        FixtureService::generate(Team::factory(2)->create());

        (new SimulationService())->simulateNextWeek($repository);

        $this->assertEquals($repository->getGamesByWeek(2)->toArray()[0], $repository->comingUp()->toArray());
    }

    /**
     * @test
     * @covers \App\Repositories\GameRepository::truncate
     */
    public function truncate_function_should_delete_all_games()
    {
        FixtureService::generate(Team::factory(2)->create());
        $repository = new GameRepository();
        $repository->truncate();
        $this->assertEquals(0, Game::count());
    }

    /**
     * @test
     * @covers \App\Repositories\GameRepository::generate
     */
    public function generate_function_should_generate_games()
    {
        $repository = new GameRepository();
        $repository->generate(new TeamRepository());
        $this->assertEquals(Game::count(), Game::with('home', 'away')->orderBy('week')->get()->count());
    }

}
