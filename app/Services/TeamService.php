<?php

namespace App\Services;

use App\Implementations\TeamRepository;

class TeamService
{
    protected $teamRepositary;

    public function __construct(TeamRepository $teamRepositary)
    {
        $this->teamRepositary = $teamRepositary;
    }

    public function getAllTeams()
    {
        return $this->teamRepositary->all();
    }
}