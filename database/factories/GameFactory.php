<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'home_team_id' => Team::factory()->create()->id,
            'away_team_id' => Team::factory()->create()->id,
            'week' => $this->faker->numberBetween(1, 20),
            'home_goals' => $this->faker->numberBetween(0, 100),
            'away_goals' => $this->faker->numberBetween(0, 100),
            'is_played' => $this->faker->boolean,
        ];
    }

    public function home(Team $team)
    {
        return $this->state(function ($attributes) use ($team) {
            return array_merge($attributes, [
                'home_team_id' => $team->id,
            ]);
        });
    }

    public function away(Team $team)
    {
        return $this->state(function ($attributes) use ($team) {
            return array_merge($attributes, [
                'away_team_id' => $team->id,
            ]);
        });
    }

    public function homeWins()
    {
        return $this->state(fn($attributes) => array_merge($attributes, [
            'is_played' => true,
            'home_goals' => $this->faker->numberBetween(2, 5),
            'away_goals' => $this->faker->numberBetween(0, 1),
        ]));
    }

    public function homeGoals(int $count = 1)
    {
        return $this->state(fn($attributes) => array_merge($attributes, [
            'home_goals' => $count,
        ]));
    }


    public function awayWins()
    {
        return $this->state(fn($attributes) => array_merge($attributes, [
            'is_played' => true,
            'home_goals' => $this->faker->numberBetween(0, 1),
            'away_goals' => $this->faker->numberBetween(2, 5),
        ]));
    }

    public function awayGoals(int $count = 1)
    {
        return $this->state(fn($attributes) => array_merge($attributes, [
            'away_goals' => $count,
        ]));
    }

    public function noGoals()
    {
        return $this->state(fn($attributes) => array_merge($attributes, [
            'is_played' => true,
            'home_goals' => 0,
            'away_goals' => 0,
        ]));
    }

    public function draw()
    {
        return $this->state(function ($attributes) {
            $score = $this->faker->numberBetween(0, 5);

            return array_merge($attributes, [
                'is_played' => true,
                'home_goals' => $score,
                'away_goals' => $score,
            ]);
        });
    }

    public function notPlayed()
    {
        return $this->state(fn($attributes) => array_merge($attributes, [
            'is_played' => false,
        ]));
    }

}
