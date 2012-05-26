<?php

	function player_fg_percentage($player) {
		return ($player['fg_attempted'] != 0) ? ( round($player['fg_made'] / $player['fg_attempted'] * 100) ) : 0;
	}
	function player_ft_percentage($player) {
		return ($player['ft_attempted'] != 0) ? ( round($player['ft_made'] / $player['ft_attempted'] * 100) ) : 0;
	}
	function player_three_percentage($player) {
		return ($player['three_attempted'] != 0) ? ( round($player['three_made'] / $player['three_attempted'] * 100) ) : 0;
	}
	function sort_fg($a, $b) {
	  if ($a['fgp'] == $b['fgp']) {
	  	return 0;
	  }
	  return ($a['fgp'] > $b['fgp']) ? -1 : 1;
	}
	function sort_ft($a, $b) {
	  if ($a['ftp'] == $b['ftp']) {
	  	return 0;
	  }
	  return ($a['ftp'] > $b['ftp']) ? -1 : 1;
	}
	function sort_three($a, $b) {
	  if ($a['three_p'] == $b['three_p']) {
	  	return 0;
	  }
	  return ($a['three_p'] > $b['three_p']) ? -1 : 1;
	}

	// Output HTML list items for player game totals
	function list_leaders($stat, $home, $away, $qry="") {
		$more_class = ($qry != "") ? 'show-more' : "";
		$results = load_leaders($stat);
		$html = "";
		$t1 = 0; // keep track of players with 0 of given stat
		$t2 = 0; // for both teams
		foreach ($results as $player) {
			if ($player[$stat] > 0) {
				$html .= "<li><span class='$more_class team $player[abr]'>" . 
								 "<span class='name'>$player[first_name] $player[last_name]</span>" . 
								 "<span class='stat'>$player[$stat]</span></span></li>";
				if ($qry) {
					$html .= table_for_events($player, $qry($player));
				}
			} else {
				($player['team_id'] == $home['team_id']) ? $t1++ : $t2++;
			}
		}
		$html .= "<li class='zero'>$t1 $home[name] with 0<span>$t2 $away[name] with 0</span></li>";
		return $html;
	}
	
	// Add shooting percentages as stats in player array
	function compute_shooting_percentages($player_array) {
		foreach ($player_array as &$player) {
			$player['fgp'] = player_fg_percentage($player);
			$player['ftp'] = player_ft_percentage($player);
			$player['three_p'] = player_three_percentage($player);
		}
		return $player_array;
	}
	$all_players = compute_shooting_percentages($all_players);
	
	// Output HTML list items for percentage stats
	function list_fg_percentage_leaders($player_array, $home, $away, $qry="") {
		$more_class = (true) ? 'show-more' : "";
		usort($player_array, 'sort_fg');
		$t1 = 0; 
		$t2 = 0; 
		foreach ($player_array as $player) {
			if ($player['fgp'] > 0) {
				$html .= "<li><span class='$more_class team $player[abr] fgp' data-id='$player[person_id]'>" . 
								 "<span class='name'>$player[first_name] $player[last_name]</span>" . 
								 "<span class='stat'>$player[fgp]%</span>" .
								 "<div class='info'>($player[fg_made]/$player[fg_attempted])</div></span></li>";
				if ($qry) {
					$html .= "<li id='more-shotchart-$player[person_id]' class='shotchart' style='display:none'></li>";
				}
			} else {
				($player['team_id'] == $home['team_id']) ? $t1++ : $t2++;
			}
		}
		$html .= "<li class='zero'>$t1 $home[name] at 0%<span>$t2 $away[name] at 0%</span></li>";
		return $html;
	}
	function list_ft_percentage_leaders($player_array, $home, $away, $qry="") {
		$more_class = ($qry != "") ? 'show-more' : "";
		usort($player_array, 'sort_ft');
		$t1 = 0; 
		$t2 = 0; 
		foreach ($player_array as $player) {
			$away_class = ($player['team_id'] == $away['team_id']) ? 'away' : "";
			if ($player['ftp'] > 0) {
				$html .= "<li><span class='$more_class team $player[abr]'>" . 
								 "<span class='name'>$player[first_name] $player[last_name]</span>" . 
								 "<span class='stat'>$player[ftp]</span>" .
								 "<div class='info'>($player[ft_made]/$player[ft_attempted])</div></li>";
				if ($qry) {
					$html .= table_for_events($player, $qry($player));
				}
			} else {
				($player['team_id'] == $home['team_id']) ? $t1++ : $t2++;
			}
		}
		$html .= "<li class='zero'>$t1 $home[name] at 0%<span>$t2 $away[name] at 0%</span></li>";
		return $html;
	}
	function list_three_percentage_leaders($player_array, $home, $away, $qry="") {
		$more_class = ($qry != "") ? 'show-more' : "";
		usort($player_array, 'sort_three');
		$t1 = 0; 
		$t2 = 0; 
		foreach ($player_array as $player) {
			$away_class = ($player['team_id'] == $away['team_id']) ? 'away' : "";
			if ($player['three_p'] > 0) {
				$html .= "<li><span class='$more_class team $player[abr]'>" . 
								 "<span class='name'>$player[first_name] $player[last_name]</span>" . 
								 "<span class='stat'>$player[three_p]</span>" .
								 "<div class='info'>($player[three_made]/$player[three_attempted])</div></li>";
				if ($qry) {
					$html .= table_for_events($player, $qry($player));
				}
			} else {
				($player['team_id'] == $home['team_id']) ? $t1++ : $t2++;
			}
		}
		$html .= "<li class='zero'>$t1 $home[name] at 0%<span>$t2 $away[name] at 0%</span></li>";
		return $html;
	}
	
	// Output HTML tables for play by play data given a player and events from db
	function table_for_events($player, $results) {
		$html = "<li id='more-event-$player[person_id]' class='more' style='display:none'><table class='more'>";
		foreach ($results as $event) {
			$html .= "<tr><td>$event[period]</td><td>$event[description]</td></tr>";
		}
		return ($html .= "</table></li>");
	}
	
?>