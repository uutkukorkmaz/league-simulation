<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends Model
{

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'won',
        'lost',
        'drawn',
        'goals_for',
        'goals_against',
    ];

    /**
     * The attributes that append to the model's JSON form.
     *
     * @var array<string, string>
     */
    protected $appends = [
        'points',
        'goal_diff',
        'standings',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'goal_diff' => 'integer',
        'points' => 'integer',
        'strength' => 'float',
    ];


    /**
     * Get the strength point.
     *
     * @return Attribute
     */
    public function strength(): Attribute
    {
        return new Attribute(get: fn() => $this->points
            + $this->getMultipliedScoreByGoals('home', 'for')
            + $this->getMultipliedScoreByGoals('home', 'against')
            + $this->getMultipliedScoreByGoals('away', 'for')
            + $this->getMultipliedScoreByGoals('away', 'against'));
    }

    /**
     * Calculate a portion of the strength based on the number of goals.
     *
     * @param  string  $side
     * @param  string  $type
     *
     * @return float
     */
    protected function getMultipliedScoreByGoals(string $side, string $type): float
    {
        return $this->{Str::camel('goals '.$type)}($side)
            * config('league.rules.strength.goals.'.$type.'.'.$side);
    }

    /**
     * Get the points in league.
     *
     * @return Attribute
     */
    public function points(): Attribute
    {
        return new Attribute(get: fn() => $this->won * config('league.rules.points.win')
            + $this->lost * config('league.rules.points.loss')
            + $this->drawn * config('league.rules.points.draw'));
    }

    /**
     * Get the goal difference.
     *
     * @return Attribute
     */
    public function goalDiff(): Attribute
    {
        return new Attribute(get: fn() => $this->goals_for - $this->goals_against);
    }

    /**
     * Get the standings.
     */
    public function standings(): Attribute
    {
        if (!$this->relationLoaded('homeStandings')) {
            $this->load('homeStandings');
        }

        if (!$this->relationLoaded('awayStandings')) {
            $this->load('awayStandings');
        }


        return new Attribute(get: fn() => $this->homeStandings->merge($this->awayStandings));
    }

    public function homeStandings(): HasMany
    {
        return $this->hasMany(Standing::class, 'home_team_id')->orderBy('week');
    }

    public function awayStandings(): HasMany
    {
        return $this->hasMany(Standing::class, 'away_team_id')->orderBy('week');
    }

    /**
     * Get the total received goals.
     *
     * @param  string  $side
     *
     * @return int
     */
    public function goalsAgainst(string $side): int
    {
        $side = strtolower($side);

        return $this->{$side.'Games'}->sum($side == 'home' ? 'away_goals' : 'home_goals');
    }


    /**
     * Get the total scored goals.
     *
     * @param  string  $side
     *
     * @return int
     */
    public function goalsFor(string $side): int
    {
        $side = strtolower($side);

        return $this->{$side.'Games'}->sum($side.'_goals');
    }

}
