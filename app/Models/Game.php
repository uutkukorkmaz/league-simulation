<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Game extends Model
{

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'home_team_id',
        'away_team_id',
        'week',
        'home_goals',
        'away_goals',
        'is_played',
    ];

    /**
     * The attributes that append to the model's JSON form.
     *
     * @var array<string, string>
     */
    protected $appends = [
        'result',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_played' => 'boolean',
        'result' => 'string',
    ];

    public function home(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function away(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    /**
     * Get the result enum.
     *
     * @return Attribute
     */
    public function result(): Attribute
    {
        return new Attribute(get: function (): string {
            if (!$this->is_played) {
                return 'NOT_PLAYED';
            }

            switch (true) {
                case $this->home_goals > $this->away_goals:
                    return 'HOME_WIN';
                case $this->home_goals < $this->away_goals:
                    return 'AWAY_WIN';
                default:
                case $this->home_goals == $this->away_goals:
                    return 'DRAW';
            }
        });
    }

}
