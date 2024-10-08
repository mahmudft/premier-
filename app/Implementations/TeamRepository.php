<?php


namespace App\Implementations;

use App\Models\Team;
use App\Repositories\TeamRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TeamRepository implements TeamRepositoryInterface
{
    protected $team;

    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    public function all(): Collection
    {
        return $this->team->all();
    }

    public function find($id): Team
    {
        return $this->team->find($id);
    }

    public function create(array $data): Team
    {
        return $this->team->create($data);
    }
}
