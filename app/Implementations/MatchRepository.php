<?php

namespace App\Implementations;

use App\Models\Matchs;
use App\Repositories\MatchRepositoryInterface;

class MatchRepository implements MatchRepositoryInterface
{
    protected $match;

    public function __construct(Matchs $match)
    {
        $this->match = $match;
    }

    public function findByWeek($week)
    {
        return $this->match->where('week', $week)->get();
    }

    public function create(array $data)
    {
        return $this->match->create($data);
    }

    public function updateScore($matchId, $homeScore, $awayScore) // Implement this method
    {
        $match = $this->match->find($matchId);
        $match->home_score = $homeScore;
        $match->away_score = $awayScore;
        $match->save();

        return $match;
    }
}
