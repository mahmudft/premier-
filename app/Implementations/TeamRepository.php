<?php


namespace App\Implementations;

use App\Models\Team;
use App\Repositories\TeamRepositoryInterface;

class TeamRepository implements TeamRepositoryInterface
{
    protected $team;

    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    public function all()
    {
        return $this->team->all();
    }

    public function find($id)
    {
        return $this->team->find($id);
    }

    public function create(array $data)
    {
        return $this->team->create($data);
    }
}
