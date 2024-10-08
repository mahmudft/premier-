<?php

namespace App\Implementations;

use App\Models\Matchs;
use App\Models\Team;
use App\Repositories\MatchRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class MatchRepository implements MatchRepositoryInterface
{
    protected $match;
    protected $team;

    public function __construct(Matchs $match, Team $team)
    {
        $this->match = $match;
    }
    public function findByWeek(int $week): Collection
    {
        return $this->match->with('homeTeam')->with('awayTeam')->where('week', $week)->get();
    }
    public function create(array $data): Matchs
    {
        return $this->match->create($data);
    }

}
