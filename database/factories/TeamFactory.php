<?php

namespace Database\Factories;

use JetBrains\PhpStorm\ArrayShape;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape(['name' => "string"])]
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
        ];
    }

    public function withName(string $name): TeamFactory
    {
        return $this->state(function ($attributes) use ($name) {
            return array_merge($attributes, [
                'name' => $name,
            ]);
        });
    }

    public function withGoals(): TeamFactory
    {
        return $this->state(function ($attributes) {
            return array_merge($attributes, [
                'goals_for' => $this->faker->numberBetween(0, 100),
                'goals_against' => $this->faker->numberBetween(0, 100),
            ]);
        });
    }

    public function withGoalsFor($count = null): TeamFactory
    {
        return $this->state(function ($attributes) use ($count) {
            return array_merge($attributes, [
                'goals_for' => $count ?? $this->faker->numberBetween(0, 100),
            ]);
        });
    }

    public function withGoalsAgainst($count = null): TeamFactory
    {
        return $this->state(function ($attributes) use ($count) {
            return array_merge($attributes, [
                'goals_against' => $count ?? $this->faker->numberBetween(0, 100),
            ]);
        });
    }

    public function withWin($count = null): TeamFactory
    {
        return $this->state(function (array $attributes) use ($count) {
            return array_merge($attributes, [
                'won' => $count ?? $this->faker->numberBetween(0, 10),
            ]);
        });
    }

    public function withLose($count = null): TeamFactory
    {
        return $this->state(function (array $attributes) use ($count) {
            return array_merge($attributes, [
                'lost' => $count ?? $this->faker->numberBetween(0, 10),
            ]);
        });
    }

    public function withDraw($count = null): TeamFactory
    {
        return $this->state(function (array $attributes) use ($count) {
            return array_merge($attributes, [
                'drawn' => $count ?? $this->faker->numberBetween(0, 10),
            ]);
        });
    }

}
