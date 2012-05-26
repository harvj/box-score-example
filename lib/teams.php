<?php
	function fg_percentage($team) {
		return ( round($team['fg_made'] / $team['fg_attempted'] * 100) );
	}
	function ft_percentage($team) {
		return ( round($team['ft_made'] / $team['ft_attempted'] * 100) );
	}
	function three_percentage($team) {
		return ( round($team['three_made'] / $team['three_attempted'] * 100) );
	}
	
	function stat_share($t1, $t2, $stat) {
		return ( floor($t1[$stat] / ($t1[$stat] + $t2[$stat]) * 100) );
	}
	
	function compare($t1, $t2, $stat) {
		if ($stat == 'turnovers' || $stat == 'fouls' || $stat == 'blocks_against') {
			return ($t1[$stat] < $t2[$stat]) ? 'home-adv' : 'away-adv';
		} else {
			return ($t1[$stat] > $t2[$stat]) ? 'home-adv' : 'away-adv';
		}
	}
	
	function calc_compare($t1_stat, $t2_stat) {
		return ($t1_stat > $t2_stat) ? 'home-adv' : 'away-adv';
	}
	function calc_diff($t1_stat, $t2_stat) {
		return abs($t1_stat - $t2_stat);
	}
	
	function stat_box($t1, $t2, $stat, $title) {
		return "<div class='category " . compare($t1, $t2, $stat) . "'>"
			. "<div class='title'>" . $title . "</div>"
			. "<div class='stat-box'>" 
			.	"<span class='team " . $t1['abr'] . "' style='height:" . stat_share($t1,$t2,$stat) . "%'>" . $t1[$stat] . "</span>" 
			.	"<span class='team " . $t2['abr'] . "' style='height:" . stat_share($t2,$t1,$stat) . "%'>" . $t2[$stat] . "</span>" 
			. "</div>"  
			. "<div class='teams'>"
			. "<span>" . $t1['abr'] . "</span><span>" . $t2['abr'] . "</span>"
			. "</div>"
		. "</div>";
	}
	
	function calc_stat_box($t1, $t2, $t1_stat, $t2_stat, $title) {
		return "<div class='category " . calc_compare($t1_stat, $t2_stat) . "'>"
			. "<div class='title'>" . $title . "</div>"
			. "<div class='stat-box'>" 
			.	"<span class='team " . $t1['abr'] . "' style='height:" . $t1_stat . "%'>" . $t1_stat . "%</span>" 
			.	"<span class='team " . $t2['abr'] . "' style='height:" . $t2_stat . "%'>" . $t2_stat . "%</span>" 
			. "</div>"  
			. "<div class='teams'>"
			. "<span>" . $t1['abr'] . "</span><span>" . $t2['abr'] . "</span>"
			. "</div>"
		. "</div>";
	}
	
	function _stat_line($t1, $t2, $stat, $title, $info=false) {
		$str = "<tr>"
			. "<td id='home-$stat'>"
			. "<span class='team home $t1[abr]' style='width:" . stat_share($t1,$t2,$stat) . "%'>" . $t1[$stat] . "</span></td>"
			. "<td id='all-$stat' class='category " . compare($t1, $t2, $stat) . "'><span class='title'>$title</span>"; 
			if ($info) { 
				$str .= "<span style='float:right'><img src='images/list.png'></span></td>"; 
			}
		$str .= "<td id='away-$stat'>"
			. "<span class='team away $t2[abr]' style='width:" . stat_share($t2,$t1,$stat) . "%'>" . $t2[$stat] . "</span></td>"
			. "</tr>";		
		return $str;
	}
	function stat_line($t1, $t2, $stat, $title, $info=false) {
		$str = "<tr>"
			. "<td id='all-$stat' class='category " . compare($t1, $t2, $stat) . "'><span class='title'>$title</span>"; 
			if ($info) { 
				$str .= "<span style='float:right'><img src='images/list.png'></span></td>"; 
			}
		$str .= "<td id='$stat-share'>"
			. "<span class='team home $t1[abr]' style='width:" . stat_share($t1,$t2,$stat) . "%'>" . $t1[$stat] . "</span>"
			. "<span class='team away $t2[abr]' style='width:" . stat_share($t2,$t1,$stat) . "%'>" . $t2[$stat] . "</span></td>"
			. "</tr>";		
		return $str;
	}
	
	function calc_stat_line($t1, $t2, $t1_stat, $t2_stat, $title, $id, $info=false) {
		$str = "<tr>";
		$str .= "<td id='all-$id' class='category " . calc_compare($t1_stat, $t2_stat) . "'><span class='title'>$title</span>";
		if ($info) { 
			$str .= "<span style='float:right'><img src='images/list.png'></span></td><div>"; 
		}
		$str .= "<td id='$id-display' class='perc-stat'>";
		$str .= "<span class='team home $t1[abr]' style='width:$t1_stat%'>$t1_stat%</span>";
		//if ($t1_stat > $t2_stat) { 
		//  $str .= "<span class='diff'>+" . calc_diff($t1_stat,$t2_stat) . "%</span>";
		//}
		$str .= "</div><div>";
		$str .= "<span class='team away $t2[abr]' style='width:$t2_stat%'>$t2_stat%</span>";		
		//if ($t1_stat < $t2_stat) { 
		//  $str .= "<span class='diff'>+" . calc_diff($t1_stat,$t2_stat) . "%</span>";
		//}
		$str .= "</div></td></tr>";
		return $str;	
	}
	
	
?>