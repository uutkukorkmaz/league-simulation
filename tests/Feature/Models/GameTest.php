<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\Team;
use App\Models\Game;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class GameTest extends TestCase
{

    /**
     * @test
     * @covers \App\Models\Game::home
     */
    public function a_Game_must_have_a_home_team()
    {
        $team = Team::factory()->create();
        $Game = Game::factory()->home($team)->create();
        $this->assertInstanceOf(BelongsTo::class, $Game->home());
    }

    /**
     * @test
     * @covers \App\Models\Game::away
     */
    public function a_Game_must_have_an_away_team()
    {
        $team = Team::factory()->create();
        $Game = Game::factory()->away($team)->create();
        $this->assertInstanceOf(BelongsTo::class, $Game->away());
    }

    /**
     * @test
     * @covers \App\Models\Game::home
     * @covers \App\Models\Game::away
     */
    public function a_Game_must_have_both_home_and_away_teams()
    {
        $Game = Game::factory()->create();
        $this->assertInstanceOf(BelongsTo::class, $Game->home());
        $this->assertInstanceOf(BelongsTo::class, $Game->away());
    }

    /**
     * @test
     * @covers \App\Models\Game::result
     */
    public function if_home_team_scores_more_than_away_team_then_home_team_wins()
    {
        $Game = Game::factory()->homeWins()->create();
        $this->assertEquals('HOME_WIN', $Game->result);
    }

    /**
     * @test
     * @covers \App\Models\Game::result
     */
    public function if_away_team_scores_more_than_home_team_then_away_team_wins()
    {
        $Game = Game::factory()->awayWins()->create();
        $this->assertEquals('AWAY_WIN', $Game->result);
    }

    /**
     * @test
     * @covers \App\Models\Game::result
     */
    public function if_home_team_scores_equal_to_away_team_then_draw()
    {
        $Game = Game::factory()->draw()->create();
        $this->assertEquals('DRAW', $Game->result);
    }

    /**
     * @test
     * @covers \App\Models\Game::result
     */
    public function if_the_Game_has_not_been_played_then_result_is_not_played()
    {
        $Match = Game::factory()->notPlayed()->create();
        $this->assertEquals('NOT_PLAYED', $Match->result);
    }

}
