<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\TeamRepositoryInterface;
use App\Repositories\MatchRepositoryInterface;
use App\Repositories\LeagueTableRepositoryInterface;
use App\Implementations\TeamRepository;
use App\Implementations\MatchRepository;
use App\Implementations\LeagueTableRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(TeamRepositoryInterface::class, TeamRepository::class);
        $this->app->bind(MatchRepositoryInterface::class, MatchRepository::class);
        $this->app->bind(LeagueTableRepositoryInterface::class, LeagueTableRepository::class);
    }

    public function boot()
    {
        //
    }
}
