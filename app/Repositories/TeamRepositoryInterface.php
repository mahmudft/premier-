<?php
namespace App\Repositories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;

interface TeamRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): Team;
    public function create(array $data): Team;
}