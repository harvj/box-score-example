<?php
// This file contains all functions that interact with the database.
// It also defines arrays with query results which are used on view pages.

	function connect_to_db() {
		$dbcnx = @mysql_connect(SECRET, SECRET, SECRET);
		if (!$dbcnx) {
			exit('<p>Unable to connect to mysql.</p>');
		}
		if (!@mysql_select_db('radioact_boxscore')) {
			exit('<p>Can\'t connect to the boxscore database.</p>');
		}
		return $dbcnx;
	}
	$dbcnx = connect_to_db();

	function extract_results($qry_result) {
		$results_array = array();
		$i = 0;
		while ($row = mysql_fetch_array($qry_result)) {
			$results_array[$i] = $row;
			$i++;
		}
		return $results_array;
	}

	function load_game_info() {
		$game_query = @mysql_query("select * from game");
		$game = mysql_fetch_array($game_query);
		return $game;
	}
	$game = load_game_info();

	function load_teams_and_stats() {
		$team_query = @mysql_query("select * from team_stat, team where team.team_id = team_stat.team_id");
		return extract_results($team_query);
		$teams = array();
		for ($i = 0; $i < 2; $i += 1) {
			$teams[$i] = mysql_fetch_array($team_query);
		}
		return $teams;
	}
	$teams = load_teams_and_stats();
	$home = $teams[0];
	$away = $teams[1];
	
	function load_all_players() {
		$qry = "select * from player_stat, person, team where person.person_id = player_stat.person_id AND person.team_id = team.team_id";
		$result = @mysql_query($qry);
		return extract_results($result);
	}
	$all_players = load_all_players();

	function load_team_players_and_stats($team_id) {
		$player_query = @mysql_query("select * from player_stat, person where person.person_id = player_stat.person_id AND person.team_id =" .$team_id);
		$players = array();
		$i = 0;
		while ($row = mysql_fetch_array($player_query)) {
			$players[$i] = $row;
			$i++;
		}
		return $players;
	}
	$home_players = load_team_players_and_stats($home['team_id']);
	$away_players = load_team_players_and_stats($away['team_id']);

	// Common query string pieces = GLOBAL variables
	$qry_player_stat = "select * from player_stat, person where person.person_id = player_stat.person_id";
	$qry_team_player_stat = "select * from player_stat, person, team where person.person_id = player_stat.person_id " .
													"AND person.team_id = team.team_id ";
	$qry_player_event = "select event.period, event.description from person, event where person.person_id=event.person_id AND " .
											"person.person_id=";
	$qry_player_event_w_pts = "select event.pts, event.period, event.description from person, event where person.person_id=event.person_id AND " .
											"person.person_id=";
	$qry_sec_player_event = "select event.period, event.description from person, event where person.person_id=event.person_id2 AND " .
																					"person.person_id=";

	// Query for players sorted by game statistics
	function load_leaders($stat) {
		$qry = $GLOBALS['qry_team_player_stat'] . "order by $stat desc";
		$result = @mysql_query($qry);
		return extract_results($result);
	}	
	
	// Queries for player events
	function load_player_made_shots($player) {
		$qry = $GLOBALS['qry_player_event'] . "$player[person_id] AND msg_type=1";
		$result = @mysql_query($qry);
		$event_array = extract_results($result);
		$regex = '/: Made/';
		$regex2 = '/ \(\d+ AST\)/';
		foreach ($event_array as &$event) {
			$event['description'] = preg_replace($regex, '', $event['description']);
			$event['description'] = preg_replace($regex2, '', $event['description']);
		}
		return $event_array;
	}
	function load_player_missed_shots($player) {
		$qry = $GLOBALS['qry_player_event'] . "$player[person_id] AND msg_type=2";
		$result = @mysql_query($qry);
		$event_array = extract_results($result);
		$regex = '/ \(\d+ BLK\)/';
		foreach ($event_array as &$event) {
			$event['description'] = preg_replace($regex, '', $event['description']);
		}
		return $event_array;	
	}	
	function load_player_off_reb($player) {
		$qry = $GLOBALS['qry_player_event'] . "$player[person_id] AND msg_type=4 AND $player[team_id]=event.off_team_id";
		$result = @mysql_query($qry);
		return extract_results($result);	
	}
	function load_player_def_reb($player) {
		$qry = $GLOBALS['qry_player_event'] . "$player[person_id] AND msg_type=4 AND $player[team_id]!=event.off_team_id";
		$result = @mysql_query($qry);
		return extract_results($result);	
	}
	function load_player_assists($player) {
		$qry = $GLOBALS['qry_sec_player_event'] . "$player[person_id] AND msg_type=1 AND event.description like '%assist%'";
		$result = @mysql_query($qry);
		$event_array = extract_results($result);
		$regex = '/: Made \(\d+ PTS\) Assist:/';
		foreach ($event_array as &$event) {
			$event['description'] = preg_replace($regex, ',', $event['description']);
		}
		return $event_array;
	}
	function load_player_threes($player) {
		$qry = $GLOBALS['qry_player_event'] . "$player[person_id] AND event.description like '%3pt Shot%'";
		$result = @mysql_query($qry);
		$event_array = extract_results($result);
		$regex = '/ \(\d+ AST\)/';
		foreach ($event_array as &$event) {
			$event['description'] = preg_replace($regex, '', $event['description']);
		}
		return $event_array;
	}
	function load_player_steals($player) {
		$qry = $GLOBALS['qry_sec_player_event'] . "$player[person_id] AND event.description like '%steal%'";
		$result = @mysql_query($qry);
		$event_array = extract_results($result);
		$regex = '/ \(\d+ TO\)/';
		foreach ($event_array as &$event) {
			$event['description'] = preg_replace($regex, '', $event['description']);
		}
		return $event_array;
	}	
	function load_player_blocks($player) {
		$qry = $GLOBALS['qry_sec_player_event'] . "$player[person_id] AND event.description like '%block%'";
		$result = @mysql_query($qry);
		$event_array = extract_results($result);
		$regex = '/: Missed/';
		foreach ($event_array as &$event) {
			$event['description'] = preg_replace($regex, ',', $event['description']);
		}
		return $event_array;
	}
	function load_player_turnovers($player) {
		$qry = $GLOBALS['qry_player_event'] . "$player[person_id] AND event.msg_type=5";
		$result = @mysql_query($qry);
		$event_array = extract_results($result);
		$regex = '/\(\d+ ST\)/';
		foreach ($event_array as &$event) {
			$event['description'] = preg_replace($regex, '', $event['description']);
		}
		return $event_array;
	}
	function load_player_fouls($player) {
		$qry = $GLOBALS['qry_player_event'] . "$player[person_id] AND msg_type=6";
		$result = @mysql_query($qry);
		return extract_results($result);	
	}
	function load_player_foul_shots($player) {
		$qry = $GLOBALS['qry_player_event'] . "$player[person_id] AND msg_type=3";
		$result = @mysql_query($qry);
		return extract_results($result);	
	}
	
	function load_player_made_shot_locations($player_id) {
		$qry = "select locationX, locationY, msg_type from event where person_id=$player_id and msg_type=1";
		$result = @mysql_query($qry);
		return extract_results($result);
	}
	function load_player_missed_shot_locations($player_id) {
		$qry = "select locationX, locationY, msg_type from event where person_id=$player_id and msg_type=2";
		$result = @mysql_query($qry);
		return extract_results($result);
	}
	

?>