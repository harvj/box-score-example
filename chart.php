<?php
	include('lib/queries.php');
	
	// Calculates whether a given location on the court (locx,locy) is above a given line. (Or to the left in the case of vertical lines)
	function is_above($line, $locx, $locy) {
		return (($line[1]['x'] - $line[0]['x'])*($locy - $line[0]['y']) - ($line[1]['y'] - $line[0]['y'])*($locx - $line[0]['x'])) > 0;
	}
	
	function determine_zone($x, $y, $type) {
		if (is_above($GLOBALS['lineB'], $x, $y)) { // left of B
			if (is_above($GLOBALS['lineA'], $x, $y)) {
				($type == 1) ? $GLOBALS['zones'][1]['made']++ : $GLOBALS['zones'][1]['missed']++; // zone 1
			} else {
				($type == 1) ? $GLOBALS['zones'][0]['made']++ : $GLOBALS['zones'][0]['missed']++; // zone 0
			}
		} else if (is_above($GLOBALS['lineC'], $x, $y)) { // between B and C
			if (is_above($GLOBALS['lineJ'], $x, $y)) {
				($type == 1) ? $GLOBALS['zones'][3]['made']++ : $GLOBALS['zones'][3]['missed']++; // zone 3
			} else {
				($type == 1) ? $GLOBALS['zones'][2]['made']++ : $GLOBALS['zones'][2]['missed']++; // zone 2
			}
		}	else if (is_above($GLOBALS['lineD'], $x, $y)) { // between C and D
		 	if (is_above($GLOBALS['lineO'], $x, $y)) { 
				if (is_above($GLOBALS['lineK'], $x, $y)) {
					($type == 1) ? $GLOBALS['zones'][7]['made']++ : $GLOBALS['zones'][7]['missed']++; // zone 7
				} else {
					($type == 1) ? $GLOBALS['zones'][6]['made']++ : $GLOBALS['zones'][6]['missed']++; // zone 6
				}
			} else {
				if (is_above($GLOBALS['lineA'], $x, $y)) {
					($type == 1) ? $GLOBALS['zones'][5]['made']++ : $GLOBALS['zones'][5]['missed']++; // zone 7
				} else {
					($type == 1) ? $GLOBALS['zones'][4]['made']++ : $GLOBALS['zones'][4]['missed']++; // zone 6
				}
			}
		} else if (is_above($GLOBALS['lineE'], $x, $y)) { // between D and E
		 	if (is_above($GLOBALS['lineO'], $x, $y)) { 
				if (is_above($GLOBALS['lineL1'], $x, $y)) {
					($type == 1) ? $GLOBALS['zones'][10]['made']++ : $GLOBALS['zones'][10]['missed']++; // zone 10
				} else {
					($type == 1) ? $GLOBALS['zones'][6]['made']++ : $GLOBALS['zones'][6]['missed']++; // zone 6
				}
			} else {
				if (is_above($GLOBALS['lineA'], $x, $y)) {
					($type == 1) ? $GLOBALS['zones'][9]['made']++ : $GLOBALS['zones'][9]['missed']++; // zone 9
				} else {
					($type == 1) ? $GLOBALS['zones'][8]['made']++ : $GLOBALS['zones'][8]['missed']++; // zone 8
				}
			}
		} else if (is_above($GLOBALS['lineF'], $x, $y)) { // between E and F
		 	if (is_above($GLOBALS['lineO'], $x, $y)) { 
				if (is_above($GLOBALS['lineL2'], $x, $y)) {
					($type == 1) ? $GLOBALS['zones'][14]['made']++ : $GLOBALS['zones'][14]['missed']++; // zone 14
				} else {
					($type == 1) ? $GLOBALS['zones'][13]['made']++ : $GLOBALS['zones'][13]['missed']++; // zone 13
				}
			} else {
				if (is_above($GLOBALS['lineA'], $x, $y)) {
					($type == 1) ? $GLOBALS['zones'][12]['made']++ : $GLOBALS['zones'][12]['missed']++; // zone 12
				} else {
					($type == 1) ? $GLOBALS['zones'][11]['made']++ : $GLOBALS['zones'][11]['missed']++; // zone 11
				}
			}
		} else if (is_above($GLOBALS['lineG'], $x, $y)) { // between F and G
		 	if (is_above($GLOBALS['lineO'], $x, $y)) { 
				if (is_above($GLOBALS['lineM'], $x, $y)) {
					($type == 1) ? $GLOBALS['zones'][17]['made']++ : $GLOBALS['zones'][17]['missed']++; // zone 17
				} else {
					($type == 1) ? $GLOBALS['zones'][13]['made']++ : $GLOBALS['zones'][13]['missed']++; // zone 13
				}
			} else {
				if (is_above($GLOBALS['lineA'], $x, $y)) {
					($type == 1) ? $GLOBALS['zones'][16]['made']++ : $GLOBALS['zones'][16]['missed']++; // zone 16
				} else {
					($type == 1) ? $GLOBALS['zones'][15]['made']++ : $GLOBALS['zones'][15]['missed']++; // zone 15
				}
			}
		} else if (is_above($GLOBALS['lineH'], $x, $y)) { // between G and H
			if (is_above($GLOBALS['lineN'], $x, $y)) {
				($type == 1) ? $GLOBALS['zones'][19]['made']++ : $GLOBALS['zones'][19]['missed']++; // zone 19
			} else {
				($type == 1) ? $GLOBALS['zones'][18]['made']++ : $GLOBALS['zones'][18]['missed']++; // zone 18
			}
		} else { // right of H
			if (is_above($GLOBALS['lineA'], $x, $y)) {
				($type == 1) ? $GLOBALS['zones'][21]['made']++ : $GLOBALS['zones'][21]['missed']++; // zone 21
			} else {
				($type == 1) ? $GLOBALS['zones'][20]['made']++ : $GLOBALS['zones'][20]['missed']++; // zone 20
			}
		}
	}
	
	$shot_locations = array();
	if ($_GET['id']) {
		$shot_locations = array_merge($shot_locations, load_player_made_shot_locations($_GET['id']));
		$shot_locations = array_merge($shot_locations, load_player_missed_shot_locations($_GET['id']));
	}	
	
	// grid numbers to use as line coordinates for zones
	$endX  = 245; $midX1 = 220; $midX2 = 165; $midX3 =  65; 
	$hiY   = 250; $tipY = 220; $midY1 = 205; $midY2 = 160; $midY3 =  60; $lowY  =  5;
	
	// These lines divide the "zones" of the basketball court. Used to determine which zone a shot was taken in
	$lineA = array(array("x" =>  -$endX, "y" => $midY3),array("x" =>   $endX, "y" => $midY3));
	$lineB = array(array("x" => -$midX1, "y" =>  -$lowY),array("x" => -$midX1, "y" => $hiY));
	$lineC = array(array("x" => -$midX2, "y" =>  -$lowY),array("x" => -$midX2, "y" => $hiY));
	$lineD = array(array("x" => -$midX3, "y" =>  -$lowY),array("x" => -$midX3, "y" => $hiY));
	$lineE = array(array("x" =>       0, "y" =>  -$lowY),array("x" =>       0, "y" => $hiY));
	$lineF = array(array("x" =>  $midX3, "y" =>  -$lowY),array("x" =>  $midX3, "y" => $hiY));
	$lineG = array(array("x" =>  $midX2, "y" =>  -$lowY),array("x" =>  $midX2, "y" => $hiY));
	$lineH = array(array("x" =>  $midX1, "y" =>  -$lowY),array("x" =>  $midX1, "y" => $hiY));
	$lineJ = array(array("x" => -$midX1, "y" => $midY3),array("x" => -$midX2, "y" => $midY2));
	$lineK = array(array("x" => -$midX2, "y" => $midY2),array("x" => -$midX3, "y" => $midY1));
	$lineL1 = array(array("x" => -$midX3, "y" => $midY1),array("x" =>  0, "y" => $tipY));
	$lineL2 = array(array("x" =>  0, "y" => $tipY),array("x" =>  $midX3, "y" => $midY1));
	$lineM = array(array("x" =>  $midX3, "y" => $midY1),array("x" =>  $midX2, "y" => $midY2));
	$lineN = array(array("x" =>  $midX2, "y" => $midY2),array("x" =>  $midX1, "y" => $midY3));
	$lineO = array(array("x" => -$midX2, "y" => $midY2),array("x" =>  $midX2, "y" => $midY2));
	
	// Define the coordinate for the shot chart indicator in each of the 22 zones and initialize made/missed counters
	$zones = array(
		array("x" => -(($endX+$midX1)/2),  "y" => (($lowY+$midY3)/2),    "made" => 0, "missed" => 0), // 0
		array("x" => -(($endX+$midX1)/2),  "y" => (($hiY+$midY3)*(1/3)), "made" => 0, "missed" => 0), // 1
		array("x" => -(($midX1+$midX2)/2), "y" => $midY3,                "made" => 0, "missed" => 0), // 2
		array("x" => -(($midX1+$midX2)/2), "y" => $midY2,                "made" => 0, "missed" => 0), // 3
		array("x" => -(($midX2+$midX3)/2), "y" => (($lowY+$midY3)/2),    "made" => 0, "missed" => 0), // 4
		array("x" => -(($midX2+$midX3)/2), "y" => (($midY2+$midY3)/2),   "made" => 0, "missed" => 0), // 5
		array("x" => -$midX3,              "y" => (($midY1+$midY2)/2),   "made" => 0, "missed" => 0), // 6
		array("x" => -(($midX2+$midX3)/2), "y" => $midY1,                "made" => 0, "missed" => 0), // 7
		array("x" => -($midX3/2),          "y" => (($lowY+$midY3)/2),    "made" => 0, "missed" => 0), // 8
		array("x" => -($midX3/2),          "y" => (($midY2+$midY3)/2),   "made" => 0, "missed" => 0), // 9
		array("x" => -($midX3/2),          "y" => (($hiY+$midY1)/2),     "made" => 0, "missed" => 0), // 10
		array("x" => ($midX3/2),           "y" => (($lowY+$midY3)/2),    "made" => 0, "missed" => 0), // 11
		array("x" => ($midX3/2),           "y" => (($midY2+$midY3)/2),   "made" => 0, "missed" => 0), // 12
		array("x" => $midX3,               "y" => (($midY1+$midY2)/2),   "made" => 0, "missed" => 0), // 13
		array("x" => ($midX3/2),           "y" => (($hiY+$midY1)/2),     "made" => 0, "missed" => 0), // 14
		array("x" => (($midX2+$midX3)/2),  "y" => (($lowY+$midY3)/2),    "made" => 0, "missed" => 0), // 15
		array("x" => (($midX2+$midX3)/2),  "y" => (($midY2+$midY3)/2),   "made" => 0, "missed" => 0), // 16
		array("x" => (($midX2+$midX3)/2),  "y" => $midY1,                "made" => 0, "missed" => 0), // 17
		array("x" => (($midX1+$midX2)/2),  "y" => $midY3,                "made" => 0, "missed" => 0), // 18
		array("x" => (($midX1+$midX2)/2),  "y" => $midY2,                "made" => 0, "missed" => 0), // 19
		array("x" => (($endX+$midX1)/2),   "y" => (($hiY+$midY3)*(1/3)), "made" => 0, "missed" => 0), // 20
		array("x" => (($endX+$midX1)/2),   "y" => (($lowY+$midY3)/2),    "made" => 0, "missed" => 0) // 21
	);
	
	foreach ($shot_locations as $loc) {
		determine_zone($loc['locationX'], $loc['locationY'], $loc['msg_type']);
	}
	
	$exp = array(is_above($lineB, -232, 17));
	
	header('Content-type: application/json');
	header('Access-Control-Allow-Origin: *');
	echo json_encode($zones);
	
?>