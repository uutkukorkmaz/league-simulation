<?php

namespace Http\Controllers;

use Tests\TestCase;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LeagueControllerTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     * @covers \App\Http\Controllers\LeagueController::__invoke
     */
    public function overview_page_is_accessible()
    {
        $response = $this->get(route('standings'));
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function overview_page_contains_all_teams()
    {
        $teams = config('league.teams');
        $response = $this->get(route('standings'));
        $response->assertStatus(200);
        $response->assertViewHas('teams', Team::all());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }


}
