<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\Team;
use App\Models\Standing;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     * @covers \App\Models\Team::points
     */
    public function win_and_draw_rates_effect_points()
    {
        $team = Team::factory()
            ->withWin(2)
            ->withDraw(1)
            ->create();

        $expected = 2 * config('league.rules.points.win')
            + 1 * config('league.rules.points.draw')
            + 0 * config('league.rules.points.loss');

        $this->assertEquals($expected, $team->points);
    }

    /**
     * @test
     * @covers \App\Models\Team::goalDiff
     */
    public function goal_difference_is_calculated_correctly()
    {
        $team = Team::factory()
            ->withGoalsFor(3)
            ->withGoalsAgainst(1)
            ->create();

        $this->assertEquals(2, $team->goal_diff);
    }

    /**
     * @test
     * @covers \App\Models\Team::homeStandings
     */
    public function a_team_must_have_a_home_standing()
    {
        $team = Team::factory()->create();
        $standing = Standing::factory()
            ->home($team)
            ->away(Team::factory()->create())
            ->create();

        $this->assertEquals($standing->id, $team->homeStandings()->first()->id);
    }

    /**
     * @test
     * @covers \App\Models\Team::awayStandings
     */
    public function a_team_must_have_an_away_standing()
    {
        $team = Team::factory()->create();
        $standing = Standing::factory()
            ->home(Team::factory()->create())
            ->away($team)
            ->create();

        $this->assertEquals($standing->id, $team->awayStandings()->first()->id);
    }

    /**
     * @test
     * @covers \App\Models\Team::standings
     */
    public function a_team_should_have_both_side_standing()
    {
        $team = Team::factory()->create();

        $home = Standing::factory()
            ->home($team)
            ->away(Team::factory()->create())
            ->create();
        $away = Standing::factory()
            ->away($team)
            ->home(Team::factory()->create())
            ->create();

        $this->assertEquals($team->id, $away->away_team_id);
        $this->assertEquals($team->id, $home->home_team_id);
        $this->assertEquals(
            array_merge($team->homeStandings->toArray(), $team->awayStandings->toArray()),
            $team->standings->toArray()
        );
    }

    /**
     * @test
     */
    public function team_strength_calculated_correctly()
    {
        $team = Team::factory()
            ->withWin(2)
            ->withDraw(1)
            ->withLoss(0)
            ->withGoalsFor(3)
            ->withGoalsAgainst(1)
            ->create();

        $this->assertEquals(
            $team->strength,
            $team->win * config('league.rules.strength.win')
            + $team->draw * config('league.rules.strength.draw')
            + $team->loss * config('league.rules.strength.loss')
            + $team->goals_for_away * config('league.rules.strength.goals.for.away')
            + $team->goals_against_away * config('league.rules.strength.goals.against.away')
            + $team->goals_for_home * config('league.rules.strength.goals.for.home')
            + $team->goals_against_home * config('league.rules.strength.goals.against.home')
        );
    }

}
