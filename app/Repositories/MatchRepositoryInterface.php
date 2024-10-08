<?php

namespace App\Repositories;

use App\Models\Matchs;
use Illuminate\Database\Eloquent\Collection;

interface MatchRepositoryInterface
{
    public function findByWeek(int $week): Collection;
    public function create(array $data): Matchs;
}
