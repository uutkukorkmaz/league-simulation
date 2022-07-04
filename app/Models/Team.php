<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Team extends Model
{

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
        'points',
    ];

    /**
     * The attributes that append to the model's JSON form.
     *
     * @var array<string, string>
     */
    protected $appends = [
        'goal_diff',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'goal_diff' => 'integer',
    ];

    /**
     * Get the goal difference.
     *
     * @return Attribute
     */
    public function goalDiff(): Attribute
    {
        return new Attribute(get: fn() => $this->goals_for - $this->goals_against);
    }

}
