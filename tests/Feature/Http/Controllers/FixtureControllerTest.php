<?php

namespace Http\Controllers;

use Tests\TestCase;
use App\Models\Team;
use App\Services\FixtureService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FixtureControllerTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     * @covers \App\Http\Controllers\FixtureController::index
     */
    public function fixture_list_is_accessible()
    {
        $response = $this->get(route('fixture.index'));
        $response->assertStatus(200);
    }

    /**
     * @test
     * @covers \App\Http\Controllers\FixtureController::reset
     * @covers \App\Http\Controllers\FixtureController::__construct
     */
    public function resetting_fixture_list_deletes_all_games()
    {
        FixtureService::generate(Team::factory(2)->create());
        $response = $this->post(route('fixture.reset'), ['_method' => 'DELETE']);
        $response->assertStatus(302);
        $response->assertRedirect(route('fixture.index'));
        $this->assertCount(0, \App\Models\Game::all());
    }

    /**
     * @test
     * @covers \App\Http\Controllers\FixtureController::generate
     */
    public function generating_fixture_list_creates_all_games()
    {
        Team::factory(4)->create();
        $response = $this->post(route('fixture.generate'));
        $response->assertStatus(302);
        $response->assertRedirect(route('fixture.index'));
        $this->assertCount(12, \App\Models\Game::all());
    }

}
