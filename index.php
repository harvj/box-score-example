<?php
	include('application.php');
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<title>Box Score Example</title>
	<link rel="stylesheet" type="text/css" href="stylesheets/application.css"/>
	<script type="text/javascript" src="javascripts/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="javascripts/jquery.hoverIntent.min.js"></script>
	<script type="text/javascript" src="javascripts/d3.js"></script>
	<script type="text/javascript" src="javascripts/application.js"/></script>
</head>
<body>
	<div id="container">
		<div id="header">
			<div class="team-scores">
				<span class="team <?php echo $home['abr'] ?>" style="width:<?php echo stat_share($home,$away,'points') ?>%">
					<span><?php echo $home['city'] ."<br>". $home['name'] ?></span>
					<span class="points"><?php echo $home['points'] ?></span>
					<!-- <span class='team-logo <?php echo $home['abr'] ?>'><img src="images/blazer-logo.png"></span> -->
				</span>
				<span class="team <?php echo $away['abr'] ?>" style="width:<?php echo stat_share($away,$home,'points') ?>%">
					<span class="points"><?php echo $away['points'] ?></span>
					<span><?php echo $away['city'] ."<br>". $away['name'] ?></span>
					<!-- <span class='team-logo <?php echo $away['abr'] ?>'><img src="images/suns-logo.png"></span> -->
				</span>
			</div>
			<div class="details">
				<p><?php echo game_date() ?><span class="location"><?php echo $game['arena'] ." in ". $game['location']?></span></p>
			</div>
		</div>
		<div id="content">
			<div class="details">
				<div id="show-points" style="display:none">
					<h1>Points</h1>
					<span class='description'>Click player name for details.</span>
					<ul>
						<?php echo list_leaders('points', $home, $away, 'load_player_made_shots') ?>
					</ul>
				</div>
				<div id="show-fgp" style="display:none">
					<h1>Field Goal %</h1>
					<span class='description'>Click player name for shot chart.</span>
					<ul>
						<?php echo list_fg_percentage_leaders($all_players, $home, $away, 'load_player_missed_shots') ?>
					</ul>
				</div>
				<div id="show-ftp" style="display:none">
					<h1>Free Throw %</h1>
					<ul>
						<?php echo list_ft_percentage_leaders($all_players, $home, $away) ?>
					</ul>
				</div>
				<div id="show-three-p" style="display:none">
					<h1>Three Point %</h1>
					<span class='description'>Click player name for details.</span>
					<ul>
						<?php echo list_three_percentage_leaders($all_players, $home, $away, 'load_player_threes') ?>
					</ul>
				</div>
				<div id="show-assists" style="display:none">
					<h1>Assists</h1>
					<span class='description'>Click player name for details.</span>
					<ul>
						<?php echo list_leaders('assists', $home, $away, 'load_player_assists') ?>
					</ul>
				</div>
				<div id="show-off-reb" style="display:none">
					<h1>Offensive Rebounds</h1>
					<span class='description'>Click player name for details.</span>
					<ul>
						<?php echo list_leaders('off_reb', $home, $away, 'load_player_off_reb') ?>
					</ul>
				</div>
				<div id="show-def-reb" style="display:none">
					<h1>Defensive Rebounds</h1>
					<span class='description'>Click player name for details.</span>
					<ul>
						<?php echo list_leaders('def_reb', $home, $away, 'load_player_def_reb') ?>
					</ul>
				</div>
				<div id="show-steals" style="display:none">
					<h1>Steals</h1>
					<span class='description'>Click player name for details.</span>
					<ul>
						<?php echo list_leaders('steals', $home, $away, 'load_player_steals') ?>
					</ul>
				</div>
				<div id="show-blocks" style="display:none">
					<h1>Blocks</h1>
					<span class='description'>Click player name for details.</span>
					<ul>
						<?php echo list_leaders('blocks', $home, $away, 'load_player_blocks') ?>
					</ul>
				</div>
				<div id="show-turnovers" style="display:none">
					<h1>Turnovers</h1>
					<span class='description'>Click player name for details.</span>
					<ul>
						<?php echo list_leaders('turnovers', $home, $away, 'load_player_turnovers') ?>
					</ul>
				</div>
				<div id="show-fouls" style="display:none">
					<h1>Fouls</h1>
					<span class='description'>Click player name for details.</span>
					<ul>
						<?php echo list_leaders('fouls', $home, $away, 'load_player_fouls') ?>
					</ul>
				</div>
				<div id="show-foul-shots" style="display:none">
					<h1>Foul Shots</h1>
					<span class='description'>Click player name for details.</span>
					<ul>
						<?php echo list_leaders('ft_attempted', $home, $away, 'load_player_foul_shots') ?>
					</ul>
				</div>
			</div>
			<div class="overview">
				<table class="stat-lines">
					<tr><td class='first section'>Points</td><td></td></tr>
					<?php echo stat_line($home,$away,'points','Total Points',true) ?>
					<?php echo stat_line($home,$away,'q1_score','1st Quarter') ?>
					<?php echo stat_line($home,$away,'q2_score','2nd Quarter') ?>
					<?php echo stat_line($home,$away,'q3_score','3rd Quarter') ?>
					<?php echo stat_line($home,$away,'q4_score','4th Quarter') ?>
				</table>
				<table class="stat-lines">	
					<tr><td class='section'>Percentage Scoring</td><td></td></tr>
					<?php echo calc_stat_line($home,$away, fg_percentage($home), fg_percentage($away), 'Field Goal %', 'fgp', true) ?>
					<?php echo calc_stat_line($home,$away, three_percentage($home), three_percentage($away), '3 Point %', 'three-p',true) ?>
					<?php echo calc_stat_line($home,$away, ft_percentage($home), ft_percentage($away), 'Free Throw %', 'ftp',true) ?>
				</table>
				<table class="stat-lines">
					<tr><td class='section'>Situational Scoring</td><td></td></tr>
					<?php echo stat_line($home,$away,'pts_in_paint','Pts in Paint') ?>
					<?php echo stat_line($home,$away,'pts_off_turnovers','Pts off TO') ?>
					<?php echo stat_line($home,$away,'fast_break_pts','Fast Break Pts') ?>
					<tr><td class='section'>Rebounding</td><td></td></tr>
					<?php echo stat_line($home,$away,'off_reb','Offensive',true) ?>
					<?php echo stat_line($home,$away,'def_reb','Defensive',true) ?>
				</table>
				<table class="stat-lines">
					<?php echo stat_line($home,$away,'assists','Assists',true) ?>
					<?php echo stat_line($home,$away,'steals','Steals',true) ?>
					<?php echo stat_line($home,$away,'blocks','Blocks',true) ?>
					<?php echo stat_line($home,$away,'turnovers','Turnovers',true) ?>
					<tr><td class='section'>Foul Line</td><td></td></tr>
					<?php echo stat_line($home,$away,'fouls','Fouls',true) ?>
					<?php echo stat_line($home,$away,'ft_attempted','Foul Shots',true) ?>
				</table>
				<div id="instructions">Mouse over categories with <img src="images/list.png"> for player breakdown.</div>
			</div>
			<div style:"clear:both"></div>
		</div>		
	</div>
</body>
</html>