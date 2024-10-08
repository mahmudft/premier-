<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'strength'];

    public function homeMatches(): HasMany
    {
        return $this->hasMany(Matchs::class, 'home_team_id');
    }

    public function awayMatches(): HasMany
    {
        return $this->hasMany(Matchs::class, 'away_team_id');
    }

    public function getAllTeams(): Collection
    {
        return $this->team->all();
    }

}
