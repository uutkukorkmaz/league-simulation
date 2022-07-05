<?php

namespace Tests\Feature\Services;

use Tests\TestCase;
use App\Models\Team;
use App\Services\PredictionService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PredictionServiceTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     * @covers \App\Services\PredictionService::predictMatchResult
     */
    public function predict_match_result_function_should_return_an_array_of_goals()
    {
        $home = Team::factory()->withWin(1)->create();
        $away = Team::factory()->create();
        $service = new PredictionService();
        $results = $service->predictMatchResult($home, $away);
        $this->assertTrue(is_numeric(array_sum(array_values($results))));
        $this->assertArrayHasKey('home_goals', $results);
        $this->assertArrayHasKey('away_goals', $results);
    }

    /**
     * @test
     * @covers \App\Services\PredictionService::getDominance
     */
    public function home_team_should_be_dominant_if_it_has_more_goals_than_away_team()
    {
        $home = Team::factory()->withWin(1)->withGoals()->create();
        $away = Team::factory()->create();
        $service = new PredictionService();
        [$homeDominance, $awayDominance] = $service->getDominance($home->strength, $away->strength);
        $this->assertGreaterThan($awayDominance, $homeDominance);
    }

}
