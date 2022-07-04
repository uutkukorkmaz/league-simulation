<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OverviewPageTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     * @covers \App\Http\Controllers\LeagueController::overview
     */
    public function overview_page_is_accessible()
    {
        $response = $this->get(route('overview'));
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function overview_page_contains_all_teams()
    {
        $teams = config('league.teams');
        $response = $this->get(route('overview'));
        $response->assertStatus(200);
        $response->assertViewHas('teams');
        $response->assertViewHas('teams', $teams);
        $response->assertSee($teams->pluck('name'), $teams);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }


}
