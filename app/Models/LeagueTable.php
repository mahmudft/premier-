<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeagueTable extends Model
{
    use HasFactory;
    protected $fillable = ['team_id', 'matches_played', 'wins', 'draws', 'losses', 'points', 'goal_difference', 'weekly'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
