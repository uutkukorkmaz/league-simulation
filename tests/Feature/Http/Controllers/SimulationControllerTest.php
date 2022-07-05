<?php

namespace Http\Controllers;

use Tests\TestCase;
use App\Models\Team;
use App\Services\FixtureService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SimulationControllerTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     * @covers \App\Http\Controllers\SimulationController::__invoke
     * @covers \App\Http\Controllers\SimulationController::__construct
     * @covers \App\Http\Controllers\SimulationController::simulateNextWeek
     * @covers \App\Services\SimulationService::loadRelations
     */
    public function simulate_next_week_creates_next_week()
    {
        FixtureService::generate(Team::factory(4)->create());
        $response = $this->post(route('simulation'), ['week' => 'next']);
        $response->assertStatus(302);
        $response->assertRedirect(route('standings'));
        $this->assertCount(2, \App\Models\Game::where('is_played', true)->get());
    }

    /**
     * @test
     * @covers \App\Http\Controllers\SimulationController::__invoke
     * @covers \App\Http\Controllers\SimulationController::__construct
     * @covers \App\Http\Controllers\SimulationController::simulateAllWeeks
     */
    public function simulate_all_weeks_creates_all_weeks()
    {
        FixtureService::generate(Team::factory(4)->create());
        $response = $this->post(route('simulation'), ['week' => 'all']);
        $response->assertStatus(302);
        $response->assertRedirect(route('standings'));
        $this->assertCount(12, \App\Models\Game::where('is_played', true)->get());
    }

    /**
     * @test
     * @covers \App\Http\Controllers\SimulationController::__invoke
     * @covers \App\Http\Controllers\SimulationController::__construct
     * @covers \App\Http\Controllers\SimulationController::simulateAllWeeks
     * @covers \App\Services\SimulationService::simulateNextWeek
     * @covers \App\Services\SimulationService::simulate
     * @covers \App\Services\SimulationService::getWeekCount
     */
    public function simulate_all_weeks_after_simulate_one_week()
    {
        FixtureService::generate(Team::factory(4)->create());

        $response = $this->post(route('simulation'), ['week' => 'next']);
        $response->assertStatus(302);
        $response->assertRedirect(route('standings'));
        $this->assertCount(2, \App\Models\Game::where('is_played', true)->get());

        $response = $this->post(route('simulation'), ['week' => 'all']);
        $response->assertStatus(302);
        $response->assertRedirect(route('standings'));
        $this->assertCount(12, \App\Models\Game::where('is_played', true)->get());
    }

}
