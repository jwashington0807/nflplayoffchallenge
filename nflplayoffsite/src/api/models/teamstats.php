<?php

class TeamStats
{
    function __construct() {
        $this -> sacks = 0;
        $this -> fumbles = 0;
        $this -> interceptions = 0;
        $this -> touchdowns = 0;
        $this -> allowedyards = 0;
        $this -> allowedpoints = 0;
        $this -> safeties = 0;
    }

	public $teamid;
	public $team;
    public $sacks;
    public $fumbles;
    public $interceptions;
    public $touchdowns;
    public $allowedyards;
    public $allowedpoints;
    public $safeties;
}

?>