<?php
namespace App\Repositories;

use App\Models\Team;

interface TeamRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
}