<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'strength'];

    public function homeMatches()
    {
        return $this->hasMany(Matchs::class, 'home_team_id');
    }

    public function awayMatches()
    {
        return $this->hasMany(Matchs::class, 'away_team_id');
    }

    public function getAllTeams(){
        return $this->team->all();
    }

}
