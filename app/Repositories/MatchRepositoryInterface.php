<?php

namespace App\Repositories;

use App\Models\Match;

interface MatchRepositoryInterface
{
    public function findByWeek($week);
    public function create(array $data);
    public function updateScore($matchId, $homeScore, $awayScore);
}
