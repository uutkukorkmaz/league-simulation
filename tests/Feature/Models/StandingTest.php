<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\Team;
use App\Models\Standing;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class StandingTest extends TestCase
{

    /**
     * @test
     * @covers \App\Models\Standing::home
     */
    public function a_standing_must_have_a_home_team()
    {
        $team = Team::factory()->create();
        $standing = Standing::factory()->home($team)->create();
        $this->assertInstanceOf(BelongsTo::class, $standing->home());
    }

    /**
     * @test
     * @covers \App\Models\Standing::away
     */
    public function a_standing_must_have_an_away_team()
    {
        $team = Team::factory()->create();
        $standing = Standing::factory()->away($team)->create();
        $this->assertInstanceOf(BelongsTo::class, $standing->away());
    }

    /**
     * @test
     * @covers \App\Models\Standing::home
     * @covers \App\Models\Standing::away
     */
    public function a_standing_must_have_both_home_and_away_teams()
    {
        $standing = Standing::factory()->create();
        $this->assertInstanceOf(BelongsTo::class, $standing->home());
        $this->assertInstanceOf(BelongsTo::class, $standing->away());
    }

    /**
     * @test
     * @covers \App\Models\Standing::result
     */
    public function if_home_team_scores_more_than_away_team_then_home_team_wins()
    {
        $standing = Standing::factory()->homeWins()->create();
        $this->assertEquals('HOME_WIN', $standing->result);
    }

    /**
     * @test
     * @covers \App\Models\Standing::result
     */
    public function if_away_team_scores_more_than_home_team_then_away_team_wins()
    {
        $standing = Standing::factory()->awayWins()->create();
        $this->assertEquals('AWAY_WIN', $standing->result);
    }

    /**
     * @test
     * @covers \App\Models\Standing::result
     */
    public function if_home_team_scores_equal_to_away_team_then_draw()
    {
        $standing = Standing::factory()->draw()->create();
        $this->assertEquals('DRAW', $standing->result);
    }

    /**
     * @test
     * @covers \App\Models\Standing::result
     */
    public function if_the_match_has_not_been_played_then_result_is_not_played()
    {
        $standing = Standing::factory()->notPlayed()->create();
        $this->assertEquals('NOT_PLAYED', $standing->result);
    }

}
