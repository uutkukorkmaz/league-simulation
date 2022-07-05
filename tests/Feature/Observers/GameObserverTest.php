<?php

namespace Tests\Feature\Observers;

use Tests\TestCase;
use App\Models\Game;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GameObserverTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     * @covers \App\Observers\GameObserver::updated
     */
    public function team_statistics_should_sync_with_games_when_win_the_game()
    {
        $home = Team::factory()->create();
        $away = Team::factory()->create();
        $game = Game::factory()->home($home)->away($away)->create();
        $game->update(['home_goals' => 1, 'away_goals' => 0, 'is_played' => true]);
        $home->refresh();
        $away->refresh();
        $this->assertEquals(1, $home->goals_for);
        $this->assertEquals(0, $home->goals_against);
        $this->assertEquals(1, $home->won);
        $this->assertEquals(0, $home->drawn);
        $this->assertEquals(0, $home->lost);
    }

    /**
     * @test
     * @covers \App\Observers\GameObserver::updated
     */
    public function team_statistics_should_sync_with_games_when_lose_the_game()
    {
        $home = Team::factory()->create();
        $away = Team::factory()->create();
        $game = Game::factory()->home($home)->away($away)->create();
        $game->update(['home_goals' => 0, 'away_goals' => 1, 'is_played' => true]);
        $home->refresh();
        $away->refresh();
        $this->assertEquals(0, $home->goals_for);
        $this->assertEquals(1, $home->goals_against);
        $this->assertEquals(0, $home->won);
        $this->assertEquals(0, $home->drawn);
        $this->assertEquals(1, $home->lost);
    }

    /**
     * @test
     * @covers \App\Observers\GameObserver::updated
     */
    public function team_statistics_should_sync_with_games_when_the_game_is_draw()
    {
        $home = Team::factory()->create();
        $away = Team::factory()->create();
        $game = Game::factory()->home($home)->away($away)->create();
        $game->update(['home_goals' => 1, 'away_goals' => 1, 'is_played' => true]);
        $home->refresh();
        $away->refresh();
        $this->assertEquals(1, $home->goals_for);
        $this->assertEquals(1, $home->goals_against);
        $this->assertEquals(0, $home->won);
        $this->assertEquals(1, $home->drawn);
        $this->assertEquals(0, $home->lost);
    }

}
