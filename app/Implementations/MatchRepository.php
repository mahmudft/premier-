<?php

namespace App\Implementations;

use App\Models\Matchs;
use App\Models\Team;
use App\Repositories\MatchRepositoryInterface;

class MatchRepository implements MatchRepositoryInterface
{
    protected $match;
    protected $team;

    public function __construct(Matchs $match, Team $team)
    {
        $this->match = $match;
        $this->team  = $team;
    }

    public function findByWeek($week)
    {
        return $this->match->where('week', $week)->get();
    }

    public function create(array $data)
    {
        return $this->match->create($data);
    }

    public function calculateScore($home_score, $away_score)
    {
        $team_one_strength = intval(sqrt((int)$home_score) / 2);
        $team_second_strength  = intval(sqrt((int)$away_score) / 2);

        $home = 0;
        $away = 0;

        if ($home_score == $away_score) {
            $home = $team_one_strength;
            $away = $team_second_strength;
        } elseif ($home_score > $away_score) {
            $home = $team_one_strength + 1;
            $away = $team_second_strength;
        } else {
            $home = $team_one_strength;
            $away = $team_second_strength + 1;
        }

        return ['home' => $home, 'away' => $away];
    }

    public function createOrGetMatchByWeek($week)
    {
        $teams = $this->team->all()->toArray();
        $all_matches_by_week = $this->match->with('homeTeam')->with('awayTeam')->where('week', $week)->get();
        if ($all_matches_by_week->count() == 0) {
            shuffle($teams);
            $random_match_array = array_chunk($teams, 2);
            foreach ($random_match_array as $key => $match_couple) {
                $scoring = $this->calculateScore($match_couple[0]['strength'], $match_couple[1]['strength']);
                $this->match->create([
                    'home_team_id' =>  $match_couple[0]['id'],
                    'away_team_id' => $match_couple[1]['id'],
                    'home_score' => $scoring['home'],
                    'away_score' => $scoring['away'],
                    'week' => $week
                ]);
            }
            $all_matches_by_week = $this->match->with('homeTeam')->with('awayTeam')->where('week', $week)->get();
        }

        return $all_matches_by_week;
    }
}
