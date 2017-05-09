<?php
session_start();
date_default_timezone_set('Europe/Stockholm');

include 'db.php';
include 'user.php';

$con = mysql_connect($dbhost, $dbuser, $dbpass);
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

@mysql_select_db($dbdatabase) or die( "Unable to select database");
@require_once ('robotclasses.php');


if (isset($_POST['login']) && !empty($_POST['password'])) {
	if ($_POST['password'] == $loginpassword) {
		$_SESSION['valid'] = true;
	}
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html lang="sv" xml:lang="sv" xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Language" content="sv" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
		<meta name="language" content="sv" />
		
		<meta name="url" content="http://gremalm.se/robotsm/" />
		
		<meta name="title" content="Robot-SM - Administration" />
		<meta name="keywords" content="Robot-SM" />
		<meta name="description" content="Robot-SM" />
		<meta name="robots" content="NOINDEX, NOFOLLOW" />
		
		<link rel="stylesheet" href="style2.css" type="text/css" />
		
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		
		<script type="text/javascript" src="jquery-ui-1.8.16.custom.min.js"></script>
		<link rel="stylesheet" type="text/css" href="jquery-ui-1.8.16.custom.css" />
		<script type="text/javascript" src="jquery.json-2.2.min.js"></script>
		<link rel="stylesheet" type="text/css" href="jquery.bracket.min.css" />
		<script type="text/javascript" src="jquery.bracket.min.js"></script>
		
		<link rel="stylesheet" href="theme.blue.css" />
		<script type="text/javascript" src="jquery.tablesorter.js"></script>
		<script type="text/javascript" src="jquery.tablesorter.pager.js"></script>

		<link rel="stylesheet" href="listbox.css" />
		<script type="text/javascript" src="listbox.js"></script>

<script id="js">$(function() {

	// call the tablesorter plugin
	$("#ScoreTable").tablesorter({
		theme : 'blue',

		// sort on the first column and third column in ascending order
		sortList: [[2,1],[1,0]]
	});

});</script>
<script>
	$(function () {
		$('select.searchbox').listbox({'searchbar': true});
	});
</script>
		
		<title>Robot-SM - Administration</title>
	</head>
	<body>
		<?php	if (!isset($_SESSION['valid'])) { ?>
			<h1>Log in</h1>
			<form id="LoginForm" action="?" method="post">
				<span>Password: <span><input type="text" name="password" style="width: 200px;" value="" /><br />
				<input type="submit" name="login" value="login" />
			</form>
		<?php } else { ?>
		<?php
		//Check navigation-page
		$NavigationLevel = 0;
		if (!isset($_GET['page']) || $_GET['page'] == NULL || $_GET['page'] == '') {
			$NavigationPage = "";
		} else {
			switch ($_GET['page']) {
				case 'competitonadministration':
					$NavigationLevel = 1;
					$NavigationPage = "competitonadministration";
					
					//Check navigation-event
					if (!isset($_GET['event']) || $_GET['event'] == NULL || $_GET['event'] == '') {
						$NavigationEvent = "";
					} else {
						$NavigationEvent = $_GET['event'];
						$NavigationLevel = 2;
						if ($_POST['eventaction'] != NULL || $_POST['eventaction'] != '') {
							switch ($_POST['eventaction']) {
								case 'Create event':
									$tmpEvent = new Event();
									$tmpEvent->Name = $_POST['eventname'];
									$tmpEvent->Created = $_POST['eventcreated'];
									$tmpEvent->SaveEvent();
									break;
								case 'Save event':
									$tmpEvent = new Event();
									$tmpEvent->LoadEvent($NavigationEvent);
									$tmpEvent->Name = $_POST['eventname'];
									$tmpEvent->Created = $_POST['eventcreated'];
									$tmpEvent->SaveEvent();
									break;
								case 'Delete event':
									$query = sprintf("DELETE FROM events WHERE id='%s'",
										mysql_real_escape_string($NavigationEvent));
									mysql_query($query) or die();
									break;
							}
						}
					}
					
					//Check navigation-tournament
					if (!isset($_GET['tournament']) || $_GET['tournament'] == NULL || $_GET['tournament'] == '') {
						$NavigationTournament = "";
					} else {
						$NavigationTournament = $_GET['tournament'];
						$NavigationLevel = 3;
						if ($_POST['tournamentaction'] != NULL || $_POST['tournamentaction'] != '') {
							switch ($_POST['tournamentaction']) {
								case 'Create tournament':
									$tmpTournament = new Tournament();
									$tmpTournament->Name = $_POST['tournamentname'];
									$tmpTournament->Created = $_POST['tournamentcreated'];
									$tmpTournament->SaveTournament();
									
									//Search and find the TournamentId
									$query = sprintf("SELECT * FROM tournaments WHERE name='%s' AND created='%s'",
													mysql_real_escape_string($_POST['tournamentname']),
													mysql_real_escape_string($_POST['tournamentcreated']));
									$result = mysql_query($query) or die();
									while ($row = mysql_fetch_assoc($result)) {
										$tTournamentId = $row['id'];
									}
									
									//Add relation to Event
									RelEventTournamentNew($NavigationEvent, $tTournamentId);
									break;
								case 'Save tournament':
									$tmpTournament = new Tournament();
									$tmpTournament->LoadTournament($NavigationTournament);
									$tmpTournament->Name = $_POST['tournamentname'];
									$tmpTournament->Created = $_POST['tournamentcreated'];
									$tmpTournament->SaveTournament();
									break;
								case 'Delete tournament':
									RelEventTournamentDelete($NavigationEvent, $NavigationTournament);
									break;
							}
						}
					}
					
					//Check navigation-game
					if (!isset($_GET['game']) || $_GET['game'] == NULL || $_GET['game'] == '') {
						$NavigationGame = "";
					} else {
						$NavigationGame = $_GET['game'];
						$NavigationLevel = 4;
						if ($_POST['gameaction'] != NULL || $_POST['gameaction'] != '') {
							switch ($_POST['gameaction']) {
								case 'Create game':
									$tmpGame = new Game();
									$tmpGame->Name = $_POST['gamename'];
									$tmpGame->Created = $_POST['gamecreated'];
									$tmpGame->Gametype = $_POST['gametype'];
									$tmpGame->SaveGame();
									
									//Search and find the GameId
									$query = sprintf("SELECT * FROM games WHERE name='%s' AND created='%s' AND gametype='%s'",
													mysql_real_escape_string($_POST['gamename']),
													mysql_real_escape_string($_POST['gamecreated']),
													mysql_real_escape_string($_POST['gametype']));
									$result = mysql_query($query) or die();
									while ($row = mysql_fetch_assoc($result)) {
										$tGameId = $row['id'];
									}
									echo $tGameId;
									//Add relation to Event
									RelTournamentGameNew($NavigationTournament, $tGameId);
									break;
								case 'Save game':
									$tmpGame = new Game();
									$tmpGame->LoadGame($NavigationGame);
									$tmpGame->Name = $_POST['gamename'];
									$tmpGame->Created = $_POST['gamecreated'];
									$tmpGame->Gametype = $_POST['gametype'];
									$tmpGame->SaveGame();
									break;
								case 'Delete game':
									RelTournamentGameDelete($NavigationTournament, $NavigationGame);
									break;
							}
						}
					}
					break;
				case 'teamadministration':
					$NavigationLevel = 1;
					$NavigationPage = "teamadministration";
					
					//Check navigation-sub
					if (!isset($_GET['sub']) || $_GET['sub'] == NULL || $_GET['sub'] == '') {
						$NavigationSub = "";
					} else {
						$NavigationSub = $_GET['sub'];
						$NavigationLevel = 2;
					}
					break;
				case 'scoreadministration':
					$NavigationLevel = 1;
					$NavigationPage = "scoreadministration";
					
					//Check navigation-event
					if (!isset($_GET['event']) || $_GET['event'] == NULL || $_GET['event'] == '') {
						$NavigationEvent = "";
					} else {
						$NavigationEvent = $_GET['event'];
						$NavigationLevel = 2;
					}
					
					//Check navigation-tournament
					if (!isset($_GET['tournament']) || $_GET['tournament'] == NULL || $_GET['tournament'] == '') {
						$NavigationTournament = "";
					} else {
						$NavigationTournament = $_GET['tournament'];
						$NavigationLevel = 3;
					}
					
					//Check navigation-game
					if (!isset($_GET['game']) || $_GET['game'] == NULL || $_GET['game'] == '') {
						$NavigationGame = "";
					} else {
						$NavigationGame = $_GET['game'];
						$NavigationLevel = 4;
					}
					break;
				default:
					$NavigationPage = "";
					break;
			}
		}
		?>
		<div id="ground">
			<div id="menucontainer">
				<div class="menulevel" id="menulevel1">
					<ul>
						<li class="<?php echo $NavigationPage == 'competitonadministration' ? 'currentmenu' : ''; ?>"><a href="?page=competitonadministration" title="Competiton Administration" alt ="Competiton Administration">Competiton Administration</a></li>
						<li class="<?php echo $NavigationPage == 'teamadministration' ? 'currentmenu' : ''; ?>"><a href="?page=teamadministration" title="Team Administration" alt ="Team Administration">Team Administration</a></li>
						<li class="<?php echo $NavigationPage == 'scoreadministration' ? 'currentmenu' : ''; ?>"><a href="?page=scoreadministration" title="Score Administration" alt ="Score Administration">Score Administration</a></li>
					</ul>
					<span>&gt;</span>
				</div>
				<div class="nofloat">&nbsp;</div>
				<?php
				//Check navigation-page
				switch ($NavigationPage) {
					case 'competitonadministration':?>
						<div class="menulevel" id="menulevel2">
							<li class="">Event: </li>
							<ul>
						<?php
						$Many = new ManyCollections();
						$Many->LoadTournaments();
						foreach ($Many->Events as &$tEvent) {
							//echo $tEvent->Name;?>
							<li class="<?php echo $NavigationEvent == $tEvent->id ? 'currentmenu' : ''; ?>"><a href="?page=competitonadministration&event=<?php echo $tEvent->id?>" title="<?php echo $tEvent->Name?>" alt ="<?php echo $tEvent->Name?>"><?php echo $tEvent->Name?></a></li>
							<?php
						}?>
							<li class="<?php echo $NavigationEvent == "new" ? 'currentmenu' : ''; ?>"><a href="?page=competitonadministration&event=new" title="New event" alt ="New event">New</a></li>
							</ul>
							<span>&gt;</span>
						</div>
						<div class="nofloat">&nbsp;</div>
						
						<?php if (strlen($NavigationEvent) > 0 && $NavigationEvent != "new") { ?>
							<div class="menulevel" id="menulevel3">
								<li class="">Tournament: </li>
								<ul>
							<?php
							$tmpEvent = new Event();
							$tmpEvent->LoadEvent($NavigationEvent);
							$tmpEvent->LoadTournaments();
							foreach ($tmpEvent->Tournaments as &$tTournament) {
								//echo $tTournament->Name;?>
								<li class="<?php echo $NavigationTournament == $tTournament->id ? 'currentmenu' : ''; ?>"><a href="?page=competitonadministration&event=<?php echo $NavigationEvent."&tournament=".$tTournament->id?>" title="<?php echo $tTournament->Name?>" alt ="<?php echo $tTournament->Name?>"><?php echo $tTournament->Name?></a></li>
								<?php
							}?>
								<li class="<?php echo $NavigationTournament == "new" ? 'currentmenu' : ''; ?>"><a href="?page=competitonadministration&event=<?php echo $NavigationEvent."&tournament="?>new" title="New tournament" alt ="New tournament">New</a></li>
								</ul>
								<span>&gt;</span>
							</div>
							<div class="nofloat">&nbsp;</div>
							
							<?php if (strlen($NavigationTournament) > 0 && $NavigationTournament != "new") { ?>
								<div class="menulevel" id="menulevel4">
									<li class="">Game: </li>
									<ul>
								<?php
								$tmpTournament = new Tournament();
								$tmpTournament->LoadTournament($NavigationTournament);
								$tmpTournament->LoadGames();
								foreach ($tmpTournament->Games as &$tGame) {
									//echo $tGame->Name;?>
									<li class="<?php echo $NavigationGame == $tGame->id ? 'currentmenu' : ''; ?>"><a href="?page=competitonadministration&event=<?php echo $NavigationEvent."&tournament=".$NavigationTournament."&game=".$tGame->id?>" title="<?php echo $tGame->Name?>" alt ="<?php echo $tGame->Name?>"><?php echo $tGame->Name?></a></li>
									<?php
								}?>
									<li class="<?php echo $NavigationGame == "new" ? 'currentmenu' : ''; ?>"><a href="?page=competitonadministration&event=<?php echo $NavigationEvent."&tournament=".$NavigationTournament."&game="?>new" title="New game" alt ="New game">New</a></li>
									</ul>
									<span>&gt;</span>
								</div>
								<div class="nofloat">&nbsp;</div>
							<?php } ?>
						<?php } ?>
						<?php
						break;
					case 'teamadministration':?>
						<div class="menulevel" id="menulevel2">
							<ul>
								<li class="<?php echo $NavigationSub == "robots" ? 'currentmenu' : ''; ?>"><a href="?page=teamadministration&sub=robots" title="Robots" alt ="Robots">Robots</a></li>
								<li class="<?php echo $NavigationSub == "weighin" ? 'currentmenu' : ''; ?>"><a href="?page=teamadministration&sub=weighin" title="Weighin" alt ="Weighin">Weighin</a></li>
								<li class="<?php echo $NavigationSub == "teams" ? 'currentmenu' : ''; ?>"><a href="?page=teamadministration&sub=teams" title="Teams" alt ="Teams">Teams</a></li>
								<li class="<?php echo $NavigationSub == "participants" ? 'currentmenu' : ''; ?>"><a href="?page=teamadministration&sub=participants" title="Participants" alt ="Participants">Participants</a></li>
							</ul>
							<span>&gt;</span>
						</div>
						<div class="nofloat">&nbsp;</div>
						<?php
						break;
					case 'scoreadministration':?>
						<div class="menulevel" id="menulevel2">
							<li class="">Event: </li>
							<ul>
						<?php
						$Many = new ManyCollections();
						$Many->LoadTournaments();
						foreach ($Many->Events as &$tEvent) {
							//echo $tEvent->Name;?>
							<li class="<?php echo $NavigationEvent == $tEvent->id ? 'currentmenu' : ''; ?>"><a href="?page=scoreadministration&event=<?php echo $tEvent->id?>" title="<?php echo $tEvent->Name?>" alt ="<?php echo $tEvent->Name?>"><?php echo $tEvent->Name?></a></li>
							<?php
						}?>
							</ul>
							<span>&gt;</span>
						</div>
						<div class="nofloat">&nbsp;</div>
						
						<?php if (strlen($NavigationEvent) > 0 && $NavigationEvent != "new") { ?>
							<div class="menulevel" id="menulevel3">
								<li class="">Tournament: </li>
								<ul>
							<?php
							$tmpEvent = new Event();
							$tmpEvent->LoadEvent($NavigationEvent);
							$tmpEvent->LoadTournaments();
							foreach ($tmpEvent->Tournaments as &$tTournament) {
								//echo $tTournament->Name;?>
								<li class="<?php echo $NavigationTournament == $tTournament->id ? 'currentmenu' : ''; ?>"><a href="?page=scoreadministration&event=<?php echo $NavigationEvent."&tournament=".$tTournament->id?>" title="<?php echo $tTournament->Name?>" alt ="<?php echo $tTournament->Name?>"><?php echo $tTournament->Name?></a></li>
								<?php
							}?>
								</ul>
								<span>&gt;</span>
							</div>
							<div class="nofloat">&nbsp;</div>
							
							<?php if (strlen($NavigationTournament) > 0 && $NavigationTournament != "new") { ?>
								<div class="menulevel" id="menulevel4">
									<li class="">Game: </li>
									<ul>
								<?php
								$tmpTournament = new Tournament();
								$tmpTournament->LoadTournament($NavigationTournament);
								$tmpTournament->LoadGames();
								foreach ($tmpTournament->Games as &$tGame) {
									//echo $tGame->Name;?>
									<li class="<?php echo $NavigationGame == $tGame->id ? 'currentmenu' : ''; ?>"><a href="?page=scoreadministration&event=<?php echo $NavigationEvent."&tournament=".$NavigationTournament."&game=".$tGame->id?>" title="<?php echo $tGame->Name?>" alt ="<?php echo $tGame->Name?>"><?php echo $tGame->Name?></a></li>
									<?php
								}?>
									</ul>
									<span>&gt;</span>
								</div>
								<div class="nofloat">&nbsp;</div>
							<?php } ?>
						<?php } ?>
						<?php
						break;
				}
				?>
			</div>
			<div class="nofloat"></div>
			<div id ="content">
				<!--<p>Admin-stuff here</p>-->
				<?php
				//Check navigation-page
				switch ($NavigationPage) {
					case 'competitonadministration':
						switch ($NavigationLevel) {
							case '2':
								if ($NavigationEvent == "new") {
									?>
									<form id="SetEvent" action="?page=competitonadministration&event=<?php echo $NavigationEvent ?>" method="post">
										<span>Id: <?php echo $NavigationEvent ?><span><br />
										<span>Name of event: <span><input type="text" name="eventname" style="width: 200px;" value="" /><br />
										<span>Created: <span><input type="text" name="eventcreated" style="width: 200px;" value="<?php echo date("Y-m-d H:i:s"); ?>" /><br />
										<input type="submit" name="eventaction" value="Create event" />
									</form>
									<?php
								} else {
									$tmpEvent = new Event();
									$tmpEvent->LoadEvent($NavigationEvent); ?>
									<form id="SetEvent" action="?page=competitonadministration&event=<?php echo $NavigationEvent ?>" method="post">
										<span>Id: <?php echo $NavigationEvent ?><span><br />
										<span>Name of event: <span><input type="text" name="eventname" style="width: 200px;" value="<?php echo $tmpEvent->Name; ?>" /><br />
										<span>Created: <span><input type="text" name="eventcreated" style="width: 200px;" value="<?php echo $tmpEvent->Created; ?>" /><br />
										<input type="submit" name="eventaction" value="Save event" />
										<input type="submit" name="eventaction" value="Delete event" onclick="if(confirm('Delete event?')){return true}else{return false};" />
									</form>
									<?php
								}
								break;
							case '3':
								if ($NavigationTournament == "new") {
									?>
									<form id="SetTournament" action="?page=competitonadministration&event=<?php echo $NavigationEvent."&tournament=".$NavigationTournament ?>" method="post">
										<span>Id: <?php echo $NavigationTournament ?><span><br />
										<span>Name of tournament: <span><input type="text" name="tournamentname" style="width: 200px;" value="" /><br />
										<span>Created: <span><input type="text" name="tournamentcreated" style="width: 200px;" value="<?php echo date("Y-m-d H:i:s"); ?>" /><br />
										<input type="submit" name="tournamentaction" value="Create tournament" />
									</form>
									<?php
								} else {
									$tmpTournament = new Tournament();
									$tmpTournament->LoadTournament($NavigationTournament); ?>
									<form id="SetTournament" action="?page=competitonadministration&event=<?php echo $NavigationEvent."&tournament=".$NavigationTournament ?>" method="post">
										<span>Id: <?php echo $NavigationTournament ?><span><br />
										<span>Name of tournament: <span><input type="text" name="tournamentname" style="width: 200px;" value="<?php echo $tmpTournament->Name; ?>" /><br />
										<span>Created: <span><input type="text" name="tournamentcreated" style="width: 200px;" value="<?php echo $tmpTournament->Created; ?>" /><br />
										<input type="submit" name="tournamentaction" value="Save tournament" />
										<input type="submit" name="tournamentaction" value="Delete tournament" onclick="if(confirm('Delete tournament?')){return true}else{return false};" />
									</form>
									<?php
								}
								break;
							case '4':
								if ($NavigationGame == "new") {
									?>
									<form id="SetGame" action="?page=competitonadministration&event=<?php echo $NavigationEvent."&tournament=".$NavigationTournament."&game=".$NavigationGame ?>" method="post">
										<span>Id: <?php echo $NavigationGame ?><span><br />
										<span>Name of game: <span><input type="text" name="gamename" style="width: 200px;" value="" /><br />
										<span>Created: <span><input type="text" name="gamecreated" style="width: 200px;" value="<?php echo date("Y-m-d H:i:s"); ?>" /><br />
										<span>Gametype: <span><select name="gametype">
																<option value="roundrobin">Round-robin</option>
																<option value="doubleelimination">Double-elimination</option>
																<option value="scoreboard">Scoreboard</option>
															</select><br />
										<input type="submit" name="gameaction" value="Create game" />
									</form>
									<?php
								} else {
									$tmpGame = new Game();
									$tmpGame->LoadGame($NavigationGame); ?>
									<form id="SetGame" action="?page=competitonadministration&event=<?php echo $NavigationEvent."&tournament=".$NavigationTournament."&game=".$NavigationGame ?>" method="post">
										<span>Id: <?php echo $NavigationGame ?></span><br />
										<span>Name of game: </span><input type="text" name="gamename" style="width: 200px;" value="<?php echo $tmpGame->Name; ?>" /><br />
										<span>Created: </span><input type="text" name="gamecreated" style="width: 200px;" value="<?php echo $tmpGame->Created; ?>" /><br />
										<span>Gametype: </span><select name="gametype">
																<option value="roundrobin" <?php if($tmpGame->Gametype == "roundrobin"){echo 'selected';}else{echo '';};?>>Round-robin</option>
																<option value="doubleelimination" <?php if($tmpGame->Gametype == "doubleelimination"){echo 'selected';}else{echo '';};?>>Double-elimination</option>
																<option value="scoreboard" <?php if($tmpGame->Gametype == "scoreboard"){echo 'selected';}else{echo '';};?>>Scoreboard</option>
															</select><br />
										<input type="submit" name="gameaction" value="Save game" />
										<input type="submit" name="gameaction" value="Delete game" onclick="if(confirm('Delete game?')){return true}else{return false};" />
									</form>
									<?php
									/* Save double elemination */
									if ($_POST['gameaction'] == "Save") {
										if (is_numeric($_POST['reldoubleeliminationid'])) {
											//echo "Remove from team".$_POST['RobotsRobotId'];
											$query = sprintf("UPDATE rel_game_robot_doubleelemination SET position='%s', overunder='%s' WHERE id='%s'",
												mysql_real_escape_string($_POST['Position']),
												mysql_real_escape_string($_POST['overunder']),
												mysql_real_escape_string($_POST['reldoubleeliminationid']));
											mysql_query($query) or die();
										}
									}
									/* Save scoreboard */
									if ($_POST['gameaction'] == "Save") {
										if ($tmpGame->Gametype == "scoreboard") {
											$tmpScoreboardsColumn = new ColumnRound();
											if ($_POST['scoreboardid'] == "new") {
												$tmpScoreboardsColumn->id = 0;
											} else {
												$tmpScoreboardsColumn->id = $_POST['scoreboardid'];
											}
											$tmpScoreboardsColumn->Name = $_POST['Namee'];
											$tmpScoreboardsColumn->Position = $_POST['Position'];
											$tmpScoreboardsColumn->Gameid = $tmpGame->id;
											$tmpScoreboardsColumn->SaveColumn();
										}
									}
									
									if ($tmpGame->Gametype == "doubleelimination") {
										echo '<h3>doubleelimination</h3>';
										$query = sprintf("SELECT * FROM rel_game_robot_doubleelemination WHERE game_id='%s' ORDER BY position, overunder",
														mysql_real_escape_string($tmpGame->id));
										$result = mysql_query($query) or die();
										while ($row = mysql_fetch_assoc($result)) {
											$tmpRobot = new Robot();
											$tmpRobot->LoadRobot($row['robot_id']);
											?>
											<form id="SetGame2" <?php if($row['overunder'] == 1){echo ' class="under"';}else{echo '';};?> action="?page=competitonadministration&event=<?php echo $NavigationEvent."&tournament=".$NavigationTournament."&game=".$NavigationGame ?>" method="post">
												<input type="hidden" name="reldoubleeliminationid" value="<?php echo $row['id']; ?>"><span>Pos: </span><input type="text" name="Position" style="width: 20px;" value="<?php echo $row['position']; ?>" /> <?php echo $tmpRobot->Name; ?> <input type="checkbox" name="overunder" value="1"<?php if($row['overunder'] == 1){echo ' checked="checked"';}else{echo '';};?> />Under <input type="submit" name="gameaction" value="Save" />
											</form>
											<?php
											$RobotId = $row['robot_id'];
										}
									}
									if ($tmpGame->Gametype == "scoreboard") {
										echo '<h3>Scoreboard-Columns</h3>';
										$tmpGame->LoadColumns();
										foreach ($tmpGame->ScoreboardsColumns as &$tColumn) {
											?>
											<form id="SetGame2" action="?page=competitonadministration&event=<?php echo $NavigationEvent."&tournament=".$NavigationTournament."&game=".$NavigationGame ?>" method="post">
												<input type="hidden" name="scoreboardid" value="<?php echo $tColumn->id; ?>"><span>Pos: </span><input type="text" name="Position" style="width: 20px;" value="<?php echo $tColumn->Position; ?>" /> <input type="text" name="Namee" style="" value="<?php echo $tColumn->Name; ?>" /> <input type="submit" name="gameaction" value="Save" />
											</form>
											<?php
										}
										?>
										<br />
										<h3>New scoreboard-column</h3>
										<form id="SetGame3" action="?page=competitonadministration&event=<?php echo $NavigationEvent."&tournament=".$NavigationTournament."&game=".$NavigationGame ?>" method="post">
											<input type="hidden" name="scoreboardid" value="new"><span>Pos: </span><input type="text" name="Position" style="width: 20px;" value="0" /> <input type="text" name="Namee" style="" value="" /> <input type="submit" name="gameaction" value="Save" />
										</form>
										<?php
									}

								}
								break;
						}
						break;
					case 'teamadministration':
						switch ($NavigationSub) {
							case 'robots': ?>
								<h1>Robots:</h1>
								<?php
								if (!isset($_POST['RobotId']) || $_POST['RobotId'] == NULL || $_POST['RobotId'] == '') {
									$RobotId = "";
								} else {
									$RobotId = $_POST['RobotId'];
								}
								if ($_POST['robotaction'] == "Save robot") {
									if ($RobotId == "") {
										$tmpRobot = new Robot();
										$tmpRobot->Name = $_POST['robotname'];
										$tmpRobot->Created = $_POST['robotcreated'];
										$tmpRobot->RobotClass = $_POST['robotclass'];
										$tmpRobot->RobotClassName = $_POST['robotclassname'];
										$tmpRobot->Weight = $_POST['robotweight'];
										$tmpRobot->Width = $_POST['robotwidth'];
										$tmpRobot->Depth = $_POST['robotdepth'];
										$tmpRobot->Height = $_POST['robotheight'];
										$tmpRobot->Image = $_POST['robotimage'];
										$tmpRobot->Background = $_POST['robotbackground'];
										$tmpRobot->WeighinDate = $_POST['robotweighindate'];
										$tmpRobot->SaveRobot();
										//Search and find the RobotId
										$query = sprintf("SELECT * FROM robots WHERE robot_name='%s' AND created='%s'",
														mysql_real_escape_string($tmpRobot->Name),
														mysql_real_escape_string($tmpRobot->Created));
										$result = mysql_query($query) or die();
										while ($row = mysql_fetch_assoc($result)) {
											$RobotId = $row['robot_id'];
										}
									} else {
										$tmpRobot = new Robot();
										$tmpRobot->LoadRobot($RobotId);
										$tmpRobot->Name = $_POST['robotname'];
										$tmpRobot->Created = $_POST['robotcreated'];
										$tmpRobot->RobotClass = $_POST['robotclass'];
										$tmpRobot->RobotClassName = $_POST['robotclassname'];
										$tmpRobot->Weight = $_POST['robotweight'];
										$tmpRobot->Width = $_POST['robotwidth'];
										$tmpRobot->Depth = $_POST['robotdepth'];
										$tmpRobot->Height = $_POST['robotheight'];
										$tmpRobot->Image = $_POST['robotimage'];
										$tmpRobot->Background = $_POST['robotbackground'];
										$tmpRobot->WeighinDate = $_POST['robotweighindate'];
										$tmpRobot->SaveRobot();
									}
								}
								if ($_POST['teamaction'] == "Add to robot") {
									if (is_numeric($_POST['GlobalTeamsId'])) {
										//echo "Add to team".$_POST['GlobalTeamsId'];
										RelRobotTeamNew($RobotId, $_POST['GlobalTeamsId']);
									}
								}
								if ($_POST['teamaction'] == "Remove from robot") {
									if (is_numeric($_POST['RobotsTeamId'])) {
										//echo "Remove from team".$_POST['RobotsTeamId'];
										RelTeamParticipantDelete($RobotId, $_POST['RobotsTeamId']);
									}
								}
								if ($_POST['gameaction'] == "Add to robot") {
									if (is_numeric($_POST['GlobalRobotsId'])) {
										//echo "Add to team".$_POST['GlobalRobotsId'];
										$TempGame = new Game();
										$TempGame->LoadGame($_POST['GlobalRobotsId']);
										if ($TempGame->Gametype == "doubleelimination") {
											RelGameRobotDoubleEleminationNew($_POST['GlobalRobotsId'], $RobotId);
										} else {
											RelGameRobotNew($_POST['GlobalRobotsId'], $RobotId);
										}
									}
								}
								if ($_POST['gameaction'] == "Remove from robot") {
									if (is_numeric($_POST['RobotsRobotId'])) {
										//echo "Remove from team".$_POST['RobotsRobotId'];
										$TempGame = new Game();
										$TempGame->LoadGame($_POST['RobotsRobotId']);
										if ($TempGame->Gametype == "doubleelimination") {
											RelGameRobotDoubleEleminationDelete($_POST['RobotsRobotId'], $RobotId);
										} else {
											RelGameRobotDelete($_POST['RobotsRobotId'], $RobotId);
										}
									}
								}
								?>
								<form id="robotselect" action="?page=teamadministration&sub=robots" method="post">
									<select size="6" class="searchbox" name="RobotId" style="width: 300px; height: 250px;">
										<?php $oTmp = new ManyCollections();
										$oTmp->LoadRobots();
										foreach ($oTmp->Robots as &$tRobot) {
											if ($tRobot->id == $RobotId) { ?>
												<option value="<?php echo $tRobot->id; ?>" selected="selected"><?php echo $tRobot->Name; ?></option>
											<?php } else { ?>
												<option value="<?php echo $tRobot->id; ?>"><?php echo $tRobot->Name; ?></option>
											<?php } ?>
										<?php
										}
										?>
									</select>
									<script>
										$('#robotselect').on('click', '.lbjs-item', function() {
											document.forms['robotselect'].submit();
										});
									</script>
									<br />
									<input type="submit" name="robotaction" value="Select robot" />
								</form>
								<h2>Robot-info:</h2>
								<form action="?page=teamadministration&sub=robots" method="post">
									<?php if ($RobotId == "") { ?>
										<input type="hidden" name="RobotId" value="" />
										<span>Robot-Id: <span>New robot<br />
										<span>Name: <span><input type="text" name="robotname" style="width: 200px;" value="" /><br />
										<span>Created: <span><input type="text" name="robotcreated" style="width: 200px;" value="<?php echo date("Y-m-d H:i:s"); ?>" /><br />
										<span>RobotClass: <span><input type="text" name="robotclass" style="width: 200px;" value="" /><br />
										<span>RobotClassname: <span><input type="text" name="robotclassname" style="width: 200px;" value="" /><br />
										<span>Weight: <span><input type="text" name="robotweight" style="width: 200px;" value="" /><br />
										<span>Width: <span><input type="text" name="robotwidth" style="width: 200px;" value="" /><br />
										<span>Depth: <span><input type="text" name="robotdepth" style="width: 200px;" value="" /><br />
										<span>Height: <span><input type="text" name="robotheight" style="width: 200px;" value="" /><br />
										<span>Image: <span><input type="text" name="robotimage" style="width: 200px;" value="" /><br />
										<span>Background: <span><textarea name="robotbackground" cols="40" rows="9" /></textarea><br />
										<span>Weighin date: <span><input type="text" name="robotweighindate" style="width: 200px;" value="" /><br />
										<input type="submit" name="robotaction" value="Save robot" />
									<?php } else { 
										$oRobot = new Robot();
										$oRobot->LoadRobot($RobotId); ?>
										<input type="hidden" name="RobotId" value="<?php echo $oRobot->id; ?>" />
										<span>Robot-Id: <span><?php echo $oRobot->id; ?><br />
										<span>Name: <span><input type="text" name="robotname" style="width: 200px;" value="<?php echo $oRobot->Name; ?>" /><br />
										<span>Created: <span><input type="text" name="robotcreated" style="width: 200px;" value="<?php echo $oRobot->Created; ?>" /><br />
										<span>RobotClass: <span><input type="text" name="robotclass" style="width: 200px;" value="<?php echo $oRobot->RobotClass; ?>" /><br />
										<span>RobotClassname: <span><input type="text" name="robotclassname" style="width: 200px;" value="<?php echo $oRobot->RobotClassname; ?>" /><br />
										<span>Weight: <span><input type="text" name="robotweight" style="width: 200px;" value="<?php echo $oRobot->Weight; ?>" /><br />
										<span>Width: <span><input type="text" name="robotwidth" style="width: 200px;" value="<?php echo $oRobot->Width; ?>" /><br />
										<span>Depth: <span><input type="text" name="robotdepth" style="width: 200px;" value="<?php echo $oRobot->Depth; ?>" /><br />
										<span>Height: <span><input type="text" name="robotheight" style="width: 200px;" value="<?php echo $oRobot->Height; ?>" /><br />
										<span>Image: <span><input type="text" name="robotimage" style="width: 200px;" value="<?php echo $oRobot->Image; ?>" /><br />
										<span>Background: <span><textarea name="robotbackground" cols="40" rows="9" /><?php echo $oRobot->Background; ?></textarea><br />
										<span>Weighin date: <span><input type="text" name="robotweighindate" style="width: 200px;" value="<?php echo $oRobot->WeighinDate; ?>" /><br />
										<input type="submit" name="robotaction" value="Save robot" />
									<?php } ?>
								</form>
								<form action="?page=teamadministration&sub=robots" method="post">
									<input type="hidden" name="RobotId" value="" />
									<input type="submit" name="robotaction" value="New robot" />
								</form>
								<h2>Robots team:</h2>
								<form action="?page=teamadministration&sub=robots" method="post">
									<input type="hidden" name="RobotId" value="<?php echo $RobotId; ?>" />
									<select multiple name="RobotsTeamId" style="width: 100%; height: 250px;">
										<?php $oTmp = new Robot();
										$oTmp->LoadRobot($RobotId);
										$oTmp->LoadTeam(); ?>
											<option value="<?php echo $oTmp->oTeam->id; ?>"><?php echo $oTmp->oTeam->Name; ?></option>
									</select>
									<br />
									<input type="submit" name="teamaction" value="Remove from robot" />
								</form>
								<h2>Global teams:</h2>
								<form action="?page=teamadministration&sub=robots" method="post">
									<input type="hidden" name="RobotId" value="<?php echo $RobotId; ?>" />
									<select size="6" class="searchbox" name="GlobalTeamsId" style="width: 100%; height: 250px;">
										<?php $oTmp = new ManyCollections();
										$oTmp->LoadTeams();
										foreach ($oTmp->Teams as &$tTeam) { ?>
												<option value="<?php echo $tTeam->id; ?>"><?php echo $tTeam->Name; ?></option>
										<?php } ?>
									</select>
									<br />
									<input type="submit" name="teamaction" value="Add to robot" />
								</form>
								<h2>Robots games:</h2>
								<form action="?page=teamadministration&sub=robots" method="post">
									<input type="hidden" name="RobotId" value="<?php echo $RobotId; ?>" />
									<select multiple name="RobotsRobotId" style="width: 100%; height: 250px;">
										<?php $oTmp = new Robot();
										$query = sprintf("SELECT games.id, games.name, games.created, games.gametype FROM games INNER JOIN rel_game_robot ON games.id=rel_game_robot.game_id WHERE rel_game_robot.robot_id='%s'",
														mysql_real_escape_string($RobotId));
										$result = mysql_query($query) or die();
										while ($row = mysql_fetch_assoc($result)) { ?>
											<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
										<?php } ?>
										<?php $oTmp = new Robot();
										$query = sprintf("SELECT games.id, games.name, games.created, games.gametype FROM games INNER JOIN rel_game_robot_doubleelemination ON games.id=rel_game_robot_doubleelemination.game_id WHERE rel_game_robot_doubleelemination.robot_id='%s'",
														mysql_real_escape_string($RobotId));
										$result = mysql_query($query) or die();
										while ($row = mysql_fetch_assoc($result)) { ?>
											<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
										<?php } ?>
									</select>
									<br />
									<input type="submit" name="gameaction" value="Remove from robot" />
								</form>
								<h2>Global games:</h2>
								<form action="?page=teamadministration&sub=robots" method="post">
									<input type="hidden" name="RobotId" value="<?php echo $RobotId; ?>" />
									<select size="6" class="searchbox" name="GlobalRobotsId" style="width: 100%; height: 250px;">
										<?php $oTmp = new ManyCollections();
										$oTmp->LoadTournaments();
										foreach ($oTmp->Events as &$tEvent) {
											$tEvent->LoadTournaments();
											foreach ($tEvent->Tournaments as &$tTournament) {
												$tTournament->LoadGames();
												foreach ($tTournament->Games as &$tGame) { ?>
													<option value="<?php echo $tGame->id; ?>"><?php echo $tEvent->Name.' - '.$tTournament->Name.' - '.$tGame->Name; ?></option>
										<?php 	}
											}
										}?>
									</select>
									<br />
									<input type="submit" name="gameaction" value="Add to robot" />
								</form>
								<?php
								break;
							case 'weighin':
								echo "<h1>Weighin</h1>";
								?>
								<h2>Global games:</h2>
								<?php
								if (!isset($_POST['WeighinTournamentId']) || $_POST['WeighinTournamentId'] == NULL || $_POST['WeighinTournamentId'] == '') {
									$WeighinTournamentId = "";
								} else {
									$WeighinTournamentId = $_POST['WeighinTournamentId'];
								}
								echo 'WeighinTournamentId: '.$WeighinTournamentId;

								if (!isset($_POST['WeighinRobotId']) || $_POST['WeighinRobotId'] == NULL || $_POST['WeighinRobotId'] == '') {
									$WeighinRobotId = "";
								} else {
									$WeighinRobotId = $_POST['WeighinRobotId'];
								}
								echo ' WeighinRobotId: '.$WeighinRobotId;

								if ($_POST['weighinaction'] == "Weighin robot") {
									if (is_numeric($WeighinRobotId)) {
										$oRobot = new Robot();
										$oRobot->LoadRobot($WeighinRobotId);
										$oRobot->WeighinDate = date("Y-m-d H:i:s");
										$oRobot->SaveRobot();
									}
								}
								if ($_POST['weighinaction'] == "Remove robot") {
									if (is_numeric($WeighinRobotId)) {
										$oRobot = new Robot();
										$oRobot->LoadRobot($WeighinRobotId);
										$oRobot->WeighinDate = '';
										$oRobot->SaveRobot();
									}
								}

								if ($_POST['weighinaction'] == "Save robot") {
									echo '<h1>weighinaction Save robot</h1>';
									if ($WeighinRobotId > 0) {
										$tmpRobot = new Robot();
										$tmpRobot->LoadRobot($WeighinRobotId);
										$tmpRobot->Name = $_POST['robotname'];
										$tmpRobot->Created = $_POST['robotcreated'];
										$tmpRobot->RobotClass = $_POST['robotclass'];
										$tmpRobot->RobotClassName = $_POST['robotclassname'];
										$tmpRobot->Weight = $_POST['robotweight'];
										$tmpRobot->Width = $_POST['robotwidth'];
										$tmpRobot->Depth = $_POST['robotdepth'];
										$tmpRobot->Height = $_POST['robotheight'];
										$tmpRobot->Image = $_POST['robotimage'];
										$tmpRobot->Background = $_POST['robotbackground'];
										$tmpRobot->WeighinDate = $_POST['robotweighindate'];
										$tmpRobot->SaveRobot();
									}
								}

								?>
								<form id="TournamentSelect" action="?page=teamadministration&sub=weighin" method="post" onclick="document.forms['TournamentSelect'].submit();">
									<select multiple name="WeighinTournamentId" style="width: 100%;">
										<?php $oTmp = new ManyCollections();
										$oTmp->LoadTournaments();
										foreach ($oTmp->Events as &$tEvent) {
											$tEvent->LoadTournaments();
											foreach ($tEvent->Tournaments as &$tTournament) {
												if ($tTournament->id == $WeighinTournamentId) { ?>
													<option value="<?php echo $tTournament->id; ?>" selected="selected"><?php echo $tEvent->Name.' - '.$tTournament->Name; ?></option>
												<?php } else { ?>
													<option value="<?php echo $tTournament->id; ?>"><?php echo $tEvent->Name.' - '.$tTournament->Name; ?></option>
										<?php 	}
											}
										}?>
									</select>
									<br />
									<input type="submit" name="gameaction" value="Select game" />
								</form>
								<?php
								if ($WeighinTournamentId > 0 ) {
									$oTournament = new Tournament();
									$oTournament->LoadTournament($WeighinTournamentId);
									echo '<h2>'.$oTournament->Name.'</h2>';

									$oTournament->LoadRobots();
									$RobotsUnweight = new Collection();
									$RobotsWeight = new Collection();
									foreach ($oTournament->Robots as &$tRobot) {
										$tRobot->LoadTeam();
										if (empty($tRobot->WeighinDate)) {
											$RobotsUnweight->addItem($tRobot);
										} else {
											$RobotsWeight->addItem($tRobot);
										}
									}

									?>
									<table id="WeightinRobotSelect">
										<tr>
											<th>Unweight robots</th>
											<th>&nbsp;</th>
											<th>Weight robots</th>
										</tr>
										<tr>
											<td>
		<form id="RobotUnweightSelect" action="?page=teamadministration&sub=weighin" method="post">
			<input type="hidden" name="WeighinTournamentId" value="<?php echo $WeighinTournamentId; ?>" />
			<select size="6" class="searchbox" name="WeighinRobotId" style="width: 100%;">
				<?php
				foreach ($RobotsUnweight as &$tRobot) {
					if ($tRobot->id == $WeighinRobotId) { ?>
						<option value="<?php echo $tRobot->id; ?>" selected="selected"><?php echo $tRobot->Name.' ['.$tRobot->oTeam->Name.']'; ?></option>
					<?php } else { ?>
						<option value="<?php echo $tRobot->id; ?>"><?php echo $tRobot->Name.' ['.$tRobot->oTeam->Name.']'; ?></option>
				<?php 	}
				}?>
			</select>
			<script>
				$('#RobotUnweightSelect').on('click', '.lbjs-item', function() {
					document.forms['RobotUnweightSelect'].submit();
				});
			</script>
			<br />
			<input type="submit" name="gameaction" value="Select robot" />
		</form>
											</td>
											<td>
		<form id="RobotUnweightSelect" action="?page=teamadministration&sub=weighin" method="post" onclick="document.forms['RobotUnweightSelect'].submit();">
			<input type="hidden" name="WeighinTournamentId" value="<?php echo $WeighinTournamentId; ?>" />
			<input type="hidden" name="WeighinRobotId" value="<?php echo $WeighinRobotId; ?>" />
			<input type="submit" name="weighinaction" value="Weighin robot" /><br />
			<input type="submit" name="weighinaction" value="Remove robot" />
		</form>
											</td>
											<td>
		<form id="RobotWeightSelect" action="?page=teamadministration&sub=weighin" method="post">
			<input type="hidden" name="WeighinTournamentId" value="<?php echo $WeighinTournamentId; ?>" />
			<select size="6" class="searchbox" name="WeighinRobotId" style="width: 100%;">
				<?php
				foreach ($RobotsWeight as &$tRobot) {
					if ($tRobot->id == $WeighinRobotId) { ?>
						<option value="<?php echo $tRobot->id; ?>" selected="selected"><?php echo $tRobot->Name.' ['.$tRobot->oTeam->Name.']'; ?></option>
					<?php } else { ?>
						<option value="<?php echo $tRobot->id; ?>"><?php echo $tRobot->Name.' ['.$tRobot->oTeam->Name.']'; ?></option>
				<?php 	}
				}?>
			</select>
			<script>
				$('#RobotWeightSelect').on('click', '.lbjs-item', function() {
					document.forms['RobotWeightSelect'].submit();
				});
			</script>
			<br />
			<input type="submit" name="gameaction" value="Select robot" />
		</form>
											</td>
										</tr>
									</table>
									<?php
									if ($WeighinRobotId > 0) {
										$oRobot = new Robot();
										$oRobot->LoadRobot($WeighinRobotId);
										$oRobot->LoadTeam();

										echo '<h2>Robot - '.$oRobot->Name.' ['.$oRobot->oTeam->Name.']</h2>';
									?>
		<form id="RobotInfo" action="?page=teamadministration&sub=weighin" method="post">
			<input type="hidden" name="WeighinTournamentId" value="<?php echo $WeighinTournamentId; ?>" />
			<input type="hidden" name="WeighinRobotId" value="<?php echo $WeighinRobotId; ?>" />
			<span>Robot-Id: <span><?php echo $oRobot->id; ?><br />
			<span>Name: <span><input type="text" name="robotname" style="width: 200px;" value="<?php echo $oRobot->Name; ?>" /><br />
			<span>Created: <span><input type="text" name="robotcreated" style="width: 200px;" value="<?php echo $oRobot->Created; ?>" /><br />
			<span>RobotClass: <span><input type="text" name="robotclass" style="width: 200px;" value="<?php echo $oRobot->RobotClass; ?>" /><br />
			<span>RobotClassname: <span><input type="text" name="robotclassname" style="width: 200px;" value="<?php echo $oRobot->RobotClassname; ?>" /><br />
			<span>Weight: <span><input type="text" name="robotweight" style="width: 200px;" value="<?php echo $oRobot->Weight; ?>" /><br />
			<span>Width: <span><input type="text" name="robotwidth" style="width: 200px;" value="<?php echo $oRobot->Width; ?>" /><br />
			<span>Depth: <span><input type="text" name="robotdepth" style="width: 200px;" value="<?php echo $oRobot->Depth; ?>" /><br />
			<span>Height: <span><input type="text" name="robotheight" style="width: 200px;" value="<?php echo $oRobot->Height; ?>" /><br />
			<span>Image: <span><input type="text" name="robotimage" id="robotimage" style="width: 200px;" value="<?php echo $oRobot->Image; ?>" /><br />
			<span>Background: <span><textarea name="robotbackground" cols="40" rows="9" /><?php echo $oRobot->Background; ?></textarea><br />
			<span>Weighin date: <span><input type="text" name="robotweighindate" style="width: 200px;" value="<?php echo $oRobot->WeighinDate; ?>" /><br />
			<input type="submit" name="weighinaction" value="Save robot" />
		</form>

		<div id="results">
			<?php 
			if (strlen($oRobot->Image) > 0) {
				echo '<h3>Already existing photo</h3>';
				echo '<img src="'.$oRobot->Image.'" />';
			} else {
				echo '<h3>Your captured image will appear here...</h3>';
			}
			?>
		</div>
		<div id="my_camera"></div>
		
		<!-- First, include the Webcam.js JavaScript Library -->
		<script type="text/javascript" src="webcam.js"></script>
		
		<!-- Configure a few settings and attach camera -->
		<script language="JavaScript">
			Webcam.set({
				width: 320,
				height: 240,
				dest_width: 640,
				dest_height: 480,
				image_format: 'jpeg',
				jpeg_quality: 90
			});
			Webcam.attach( '#my_camera' );
		</script>
		
		<!-- A button for taking snaps -->
		<form>
			<input type=button value="Take Large Snapshot" onClick="take_snapshot()">
		</form>
		
		<!-- Code to handle taking the snapshot and displaying it locally -->
		<script language="JavaScript">
			function take_snapshot() {
				// take snapshot and get image data
				uploadurl = 'imageupload.php';
				Webcam.snap( function(data_uri) {
					// display results in page
					//document.getElementById('results').innerHTML = 
					//	'<h2>Here is your large image:</h2>' + 
					//	'<img src="'+data_uri+'"/>';

					$.ajax({
								url: uploadurl,
								type: "POST",
								data: data_uri
					}).done(function(data) {
						  $("#results").html( '<h3>Uploaded photo</h3><img src="./' + String.trim(data) + '" />' );
						  $("#robotimage").val( './' + String.trim(data) );
					});
				} );
			}
		</script>
									<?php
									}
								}

								break;
							case 'teams': ?>
								<h1>Teams:</h1>
								<?php
								if (!isset($_POST['TeamId']) || $_POST['TeamId'] == NULL || $_POST['TeamId'] == '') {
									$TeamId = "";
								} else {
									$TeamId = $_POST['TeamId'];
								}
								if ($_POST['teamaction'] == "Save team") {
									if ($TeamId == "") {
										$tmpTeam = new Team();
										$tmpTeam->Name = $_POST['teamname'];
										$tmpTeam->Created = $_POST['teamcreated'];
										$tmpTeam->Telephone = $_POST['teamtelephone'];
										$tmpTeam->Mail = $_POST['teammail'];
										$tmpTeam->URL = $_POST['teamurl'];
										$tmpTeam->Organisation = $_POST['teamorganisation'];
										$tmpTeam->City = $_POST['teamcity'];
										$tmpTeam->Background = $_POST['teambackground'];
										$tmpTeam->SaveTeam();
										//Search and find the GameId
										$query = sprintf("SELECT * FROM teams WHERE team_name='%s' AND created='%s'",
														mysql_real_escape_string($tmpTeam->Name),
														mysql_real_escape_string($tmpTeam->Created));
										$result = mysql_query($query) or die();
										while ($row = mysql_fetch_assoc($result)) {
											$TeamId = $row['team_id'];
										}
									} else {
										$tmpTeam = new Team();
										$tmpTeam->LoadTeam($TeamId);
										$tmpTeam->Name = $_POST['teamname'];
										$tmpTeam->Created = $_POST['teamcreated'];
										$tmpTeam->Telephone = $_POST['teamtelephone'];
										$tmpTeam->Mail = $_POST['teammail'];
										$tmpTeam->URL = $_POST['teamurl'];
										$tmpTeam->Organisation = $_POST['teamorganisation'];
										$tmpTeam->City = $_POST['teamcity'];
										$tmpTeam->Background = $_POST['teambackground'];
										$tmpTeam->SaveTeam();
									}
								}
								if ($_POST['participantaction'] == "Add to team") {
									if (is_numeric($_POST['GlobalParticipantId'])) {
										//echo "Add to team".$_POST['GlobalParticipantId'];
										RelTeamParticipantNew($TeamId, $_POST['GlobalParticipantId']);
									}
								}
								if ($_POST['participantaction'] == "Remove from team") {
									if (is_numeric($_POST['TeamParticipantId'])) {
										//echo "Remove from team".$_POST['TeamParticipantId'];
										RelTeamParticipantDelete($TeamId, $_POST['TeamParticipantId']);
									}
								}
								?>
								<form id="teamselect" action="?page=teamadministration&sub=teams" method="post">
									<select size="6" class="searchbox" name="TeamId" style="width: 100%; height: 250px;">
										<?php $oTmp = new ManyCollections();
										$oTmp->LoadTeams();
										foreach ($oTmp->Teams as &$tTeam) {
											if ($tTeam->id == $TeamId) { ?>
												<option value="<?php echo $tTeam->id; ?>" selected="selected"><?php echo $tTeam->Name; ?></option>
											<?php } else { ?>
												<option value="<?php echo $tTeam->id; ?>"><?php echo $tTeam->Name; ?></option>
											<?php } ?>
										<?php
										}
										?>
									</select>
									<script>
										$('#teamselect').on('click', '.lbjs-item', function() {
											document.forms['teamselect'].submit();
										});
									</script>
									<br />
									<input type="submit" name="teamaction" value="Select team" />
								</form>
								<h2>Team-info:</h2>
								<form action="?page=teamadministration&sub=teams" method="post">
									<?php if ($TeamId == "") { ?>
										<input type="hidden" name="TeamId" value="" />
										<span>Team-Id: <span>New team<br />
										<span>Name: <span><input type="text" name="teamname" style="width: 200px;" value="" /><br />
										<span>Created: <span><input type="text" name="teamcreated" style="width: 200px;" value="<?php echo date("Y-m-d H:i:s"); ?>" /><br />
										<span>Telephone: <span><input type="text" name="teamtelephone" style="width: 200px;" value="" /><br />
										<span>Mail: <span><input type="text" name="teammail" style="width: 200px;" value="" /><br />
										<span>URL: <span><input type="text" name="teamurl" style="width: 200px;" value="" /><br />
										<span>Organisation: <span><input type="text" name="teamorganisation" style="width: 200px;" value="" /><br />
										<span>City: <span><input type="text" name="teamcity" style="width: 200px;" value="" /><br />
										<span>Background: <span><textarea name="teambackground" cols="40" rows="9" /></textarea><br />
										<input type="submit" name="teamaction" value="Save team" />
									<?php } else { 
										$oTeam = new Team();
										$oTeam->LoadTeam($TeamId); ?>
										<input type="hidden" name="TeamId" value="<?php echo $oTeam->id; ?>" />
										<span>Team-Id: <span><?php echo $oTeam->id; ?><br />
										<span>Name: <span><input type="text" name="teamname" style="width: 200px;" value="<?php echo $oTeam->Name; ?>" /><br />
										<span>Created: <span><input type="text" name="teamcreated" style="width: 200px;" value="<?php echo $oTeam->Created; ?>" /><br />
										<span>Telephone: <span><input type="text" name="teamtelephone" style="width: 200px;" value="<?php echo $oTeam->Telephone; ?>" /><br />
										<span>Mail: <span><input type="text" name="teammail" style="width: 200px;" value="<?php echo $oTeam->Mail; ?>" /><br />
										<span>URL: <span><input type="text" name="teamurl" style="width: 200px;" value="<?php echo $oTeam->URL; ?>" /><br />
										<span>Organisation: <span><input type="text" name="teamorganisation" style="width: 200px;" value="<?php echo $oTeam->Organisation; ?>" /><br />
										<span>City: <span><input type="text" name="teamcity" style="width: 200px;" value="<?php echo $oTeam->City; ?>" /><br />
										<span>Background: <span><textarea name="teambackground" cols="40" rows="9" /><?php echo $oTeam->Background; ?></textarea><br />
										<input type="submit" name="teamaction" value="Save team" />
									<?php } ?>
								</form>
								<form action="?page=teamadministration&sub=teams" method="post">
									<input type="hidden" name="TeamId" value="" />
									<input type="submit" name="teamaction" value="New team" />
								</form>
								<h2>Team participants:</h2>
								<form action="?page=teamadministration&sub=teams" method="post">
									<input type="hidden" name="TeamId" value="<?php echo $TeamId; ?>" />
									<select multiple name="TeamParticipantId" style="width: 100%; height: 250px;">
										<?php $oTmp = new Team();
										$oTmp->LoadTeam($TeamId);
										$oTmp->LoadParticipants();
										foreach ($oTmp->Participants as &$tParticipant) { ?>
												<option value="<?php echo $tParticipant->id; ?>"><?php echo $tParticipant->Name; ?></option>
										<?php } ?>
									</select>
									<br />
									<input type="submit" name="participantaction" value="Remove from team" />
								</form>
								<h2>Global participants:</h2>
								<form action="?page=teamadministration&sub=teams" method="post">
									<input type="hidden" name="TeamId" value="<?php echo $TeamId; ?>" />
									<select size="6" class="searchbox" name="GlobalParticipantId" style="width: 100%; height: 250px;">
										<?php $oTmp = new ManyCollections();
										$oTmp->LoadParticipants();
										foreach ($oTmp->Participants as &$tParticipant) { ?>
												<option value="<?php echo $tParticipant->id; ?>"><?php echo $tParticipant->Name; ?></option>
										<?php } ?>
									</select>
									<br />
									<input type="submit" name="participantaction" value="Add to team" />
								</form>
								<?php
								break;
							case 'participants':?>
								<h1>Participants:</h1>
								<?php
								if (!isset($_POST['ParticipantId']) || $_POST['ParticipantId'] == NULL || $_POST['ParticipantId'] == '') {
									$ParticipantId = "";
								} else {
									$ParticipantId = $_POST['ParticipantId'];
								}
								if ($_POST['participantaction'] == "Save participant") {
									if ($ParticipantId == "") {
										$tmpParticipant = new Participant();
										$tmpParticipant->Name = $_POST['participantname'];
										$tmpParticipant->Created = $_POST['participantcreated'];
										$tmpParticipant->Telephone = $_POST['participanttelephone'];
										$tmpParticipant->Mail = $_POST['participantmail'];
										$tmpParticipant->SaveParticipant();
										//Search and find the GameId
										$query = sprintf("SELECT * FROM participants WHERE participant_name='%s' AND created='%s'",
														mysql_real_escape_string($tmpParticipant->Name),
														mysql_real_escape_string($tmpParticipant->Created));
										$result = mysql_query($query) or die();
										while ($row = mysql_fetch_assoc($result)) {
											$ParticipantId = $row['participant_id'];
										}
									} else {
										$tmpParticipant = new Participant();
										$tmpParticipant->LoadParticipant($ParticipantId);
										$tmpParticipant->Name = $_POST['participantname'];
										$tmpParticipant->Created = $_POST['participantcreated'];
										$tmpParticipant->Telephone = $_POST['participanttelephone'];
										$tmpParticipant->Mail = $_POST['participantmail'];
										$tmpParticipant->SaveParticipant();
									}
								}
								?>
								<form id="participantselect" action="?page=teamadministration&sub=participants" method="post">
									<select size="6" class="searchbox" name="ParticipantId" style="width: 100%; height: 250px;">
										<?php $oTmp = new ManyCollections();
										$oTmp->LoadParticipants();
										foreach ($oTmp->Participants as &$tParticipant) {
											if ($tParticipant->id == $ParticipantId) { ?>
												<option value="<?php echo $tParticipant->id; ?>" selected="selected"><?php echo $tParticipant->Name; ?></option>
											<?php } else { ?>
												<option value="<?php echo $tParticipant->id; ?>"><?php echo $tParticipant->Name; ?></option>
											<?php } ?>
										<?php
										}
										?>
									</select>
									<script>
										$('#participantselect').on('click', '.lbjs-item', function() {
											document.forms['participantselect'].submit();
										});
									</script>
									<br />
									<input type="submit" name="participantaction" value="Select team" />
								</form>
								
								<h2>Participant-info:</h2>
								<form action="?page=teamadministration&sub=participants" method="post">
									<?php if ($ParticipantId == "") { ?>
										<input type="hidden" name="ParticipantId" value="" />
										<span>Participant-Id: <span>New participant<br />
										<span>Name: <span><input type="text" name="participantname" style="width: 200px;" value="" /><br />
										<span>Created: <span><input type="text" name="participantcreated" style="width: 200px;" value="<?php echo date("Y-m-d H:i:s"); ?>" /><br />
										<span>Telephone: <span><input type="text" name="participanttelephone" style="width: 200px;" value="" /><br />
										<span>Mail: <span><input type="text" name="participantmail" style="width: 200px;" value="" /><br />
										<input type="submit" name="participantaction" value="Save participant" />
									<?php } else { 
										$oParticipant = new Participant();
										$oParticipant->LoadParticipant($ParticipantId); ?>
										<input type="hidden" name="ParticipantId" value="<?php echo $oParticipant->id; ?>" />
										<span>Participant-Id: <span><?php echo $oParticipant->id; ?><br />
										<span>Name: <span><input type="text" name="participantname" style="width: 200px;" value="<?php echo $oParticipant->Name; ?>" /><br />
										<span>Created: <span><input type="text" name="participantcreated" style="width: 200px;" value="<?php echo $oParticipant->Created; ?>" /><br />
										<span>Telephone: <span><input type="text" name="participanttelephone" style="width: 200px;" value="<?php echo $oParticipant->Telephone; ?>" /><br />
										<span>Mail: <span><input type="text" name="participantmail" style="width: 200px;" value="<?php echo $oParticipant->Mail; ?>" /><br />
										<input type="submit" name="participantaction" value="Save participant" />
									<?php } ?>
								</form>
								<form action="?page=teamadministration&sub=participants" method="post">
									<input type="hidden" name="ParticipantId" value="" />
									<input type="submit" name="participantaction" value="New participant" />
								</form>
								<?php
								break;
						}
						break;
					case 'scoreadministration':
						if ($NavigationLevel == 4) {
							$tGame = new Game();
							$tGame->LoadGame($_GET['game']);
							//echo "NavigationLevel 4 - ".$tGame->Name;
							//echo $tGame->id."<br />";
							$EventId = $_GET['event'];
							$TournamentId = $_GET['tournament'];
							$GameId = $_GET['game'];
							$MatchRobotId = $_GET['matchrobotid'];
							$MatchRobotVsId = $_GET['matchrobotvsid'];
							$MatchMatchId = $_GET['matchmatchid'];
							
							if (strlen($MatchMatchId) > 0) {
								if ($tGame->Gametype == "roundrobin") {
									if ($_POST['matchaction'] == "Save") {
										if ($MatchMatchId == "new") {
											$oMatch = new MatchRoundRobin();
											//$oMatch->LoadMatch($MatchMatchId);
											$oMatch->Gameid = $GameId;
											$oMatch->Robotid = $MatchRobotId;
											$oMatch->RobotVSid = $MatchRobotVsId;
											$oMatch->Started = $_POST['matchstarted'];
											$oMatch->Updated = $_POST['matchupdated'];
											$oMatch->WinnerRound1 = $_POST['matchwinnerround1'];
											$oMatch->WinnerRound2 = $_POST['matchwinnerround2'];
											$oMatch->WinnerRound3 = $_POST['matchwinnerround3'];
											$oMatch->Comment = $_POST['matchcomment'];
											$oMatch->SaveMatch();
											$query = sprintf("SELECT * FROM matchroundrobin WHERE started='%s'",
															mysql_real_escape_string($oMatch->Started));
											$result = mysql_query($query) or die();
											while ($row = mysql_fetch_assoc($result)) {
												$MatchMatchId = $row['id'];
											}
										} else {
											$oMatch = new MatchRoundRobin();
											$oMatch->LoadMatch($MatchMatchId);
											$oMatch->Gameid = $GameId;
											$oMatch->Robotid = $MatchRobotId;
											$oMatch->RobotVSid = $MatchRobotVsId;
											$oMatch->Started = $_POST['matchstarted'];
											$oMatch->Updated = $_POST['matchupdated'];
											$oMatch->WinnerRound1 = $_POST['matchwinnerround1'];
											$oMatch->WinnerRound2 = $_POST['matchwinnerround2'];
											$oMatch->WinnerRound3 = $_POST['matchwinnerround3'];
											$oMatch->Comment = $_POST['matchcomment'];
											$oMatch->SaveMatch();
										}
									}
								}
								if ($tGame->Gametype == "scoreboard") {
									if ($_POST['matchaction'] == "Save") {
										if ($MatchMatchId == "new") {
											$oMatch = new MatchScoreboard();
											$oMatch->Gameid = $GameId;
											$oMatch->Robotid = $MatchRobotId;
											$oMatch->Roundid = $MatchRobotVsId;
											$oMatch->Started = $_POST['matchstarted'];
											$oMatch->Updated = $_POST['matchupdated'];
											$oMatch->Comments = $_POST['matchcomment'];
											$oMatch->Score = $_POST['matchscore'];
											$oMatch->SaveMatch();
											$query = sprintf("SELECT * FROM rel_game_scoreboard_matches WHERE started='%s'",
															mysql_real_escape_string($oMatch->Started));
											$result = mysql_query($query) or die();
											while ($row = mysql_fetch_assoc($result)) {
												$MatchMatchId = $row['id'];
											}
										} else {
											$oMatch = new MatchScoreboard();
											$oMatch->LoadMatch($MatchMatchId);
											$oMatch->Gameid = $GameId;
											$oMatch->Robotid = $MatchRobotId;
											$oMatch->Roundid = $MatchRobotVsId;
											$oMatch->Started = $_POST['matchstarted'];
											$oMatch->Updated = $_POST['matchupdated'];
											$oMatch->Comments = $_POST['matchcomment'];
											$oMatch->Score = $_POST['matchscore'];
											$oMatch->SaveMatch();
										}
									}
								}
							}
							$oListMatchesDone = Collection;
							$oListMatchesTobe = Collection;
							$oListMatchesDone = new Collection();
							$oListMatchesTobe = new Collection();
							switch ($tGame->Gametype) {
								case 'roundrobin':
									$tGame->LoadMatches();
									$tGame->LoadRobots();
									?><table border="1" id="RoundRobinTable" class="roundrobin">
									<tr class="roundrobinheader"><td>&nbsp;</td><?php
									foreach ($tGame->Robots as &$tRobot) {
										if ($MatchRobotId == $tRobot->id) {
											echo '<td class="headerselected">'.$tRobot->Name.'</td>';
										} else {
											echo '<td>'.$tRobot->Name.'</td>';
										}
									}
									?></tr><?php
									$iRobotCounterOuter = 0;
									foreach ($tGame->Robots as &$tRobot) {
										echo "\n<tr>";
										if ($MatchRobotVsId == $tRobot->id) {
											echo '<td class="roundrobinsecondaryheader headersecondaryselected">'.$tRobot->Name." [".$tRobot->id."]</td>";
										} else {
											echo '<td class="roundrobinsecondaryheader">'.$tRobot->Name." [".$tRobot->id."]</td>";
										}
										//Loop 2
										$iRobotCounterInner = 0;
										foreach ($tGame->Robots as &$tRobotInner) {
											if (($tRobot->id != $tRobotInner->id) && ($iRobotCounterOuter < $iRobotCounterInner)) {
												if (($MatchRobotId == $tRobotInner->id) && ($MatchRobotVsId == $tRobot->id)) {
													echo '<td class="enabled selected">';
												} else {
													echo '<td class="enabled">';
												}
												/*echo $MatchRobotId . ' ' . $tRobotInner->id . '; ' . $MatchRobotVsId . ' ' . $tRobot->id;*/
												//echo $tRobotInner->Name;
												//Check if any match matches
												$MatchingMatchId = 0;
												$MatchingMatchStatus = 0;
												$MatchingMatchStatusIcon = "";
												foreach ($tGame->Matches as &$tMatch) {
													if ($tMatch->Robotid == $tRobotInner->id && $tMatch->RobotVSid == $tRobot->id) {
														$MatchingMatchId = $tMatch->id;
														
														$WinningsRobot = 0;
														$WinningsRobotVs = 0;
														if ($tMatch->WinnerRound1 == $tRobotInner->id) {
															$WinningsRobot += 1;
														}
														if ($tMatch->WinnerRound1 == $tRobot->id) {
															$WinningsRobotVs += 1;
														}
														if ($tMatch->WinnerRound2 == $tRobotInner->id) {
															$WinningsRobot += 1;
														}
														if ($tMatch->WinnerRound2 == $tRobot->id) {
															$WinningsRobotVs += 1;
														}
														if ($tMatch->WinnerRound3 == $tRobotInner->id) {
															$WinningsRobot += 1;
														}
														if ($tMatch->WinnerRound3 == $tRobot->id) {
															$WinningsRobotVs += 1;
														}
														//echo $WinningsRobot." ".$WinningsRobotVs;
														if ($tMatch->WinnerRound1 > -1) {
															/* Started */
															$MatchingMatchStatus = 2;
															$MatchingMatchStatusIcon = '<img src="timer.png" width="16px" height="16px" />';
														} else {
															/* Not started */
															$MatchingMatchStatus = 1;
															$MatchingMatchStatusIcon = '<img src="question.png" width="16px" height="16px" />';
														}
														/* Finished */
														if ($tMatch->WinnerRound3 > -1) {
															$MatchingMatchStatus = 3;
															$MatchingMatchStatusIcon = '<img src="ok.png" width="16px" height="16px" />';
														}
													}
												}
												if ($MatchingMatchStatus == 0) {
													$MatchingMatchStatus = 1;
													$MatchingMatchStatusIcon = '<img src="question.png" width="16px" height="16px" />';
												}
												
												$tmpString = "";
												/* Match was found when looking through matches */
												if ($MatchingMatchId != 0) {
													echo '<a href="?page=scoreadministration&event='.$EventId.'&tournament='.$TournamentId.'&game='.$GameId.'&matchrobotid='.$tRobotInner->id.'&matchrobotvsid='.$tRobot->id.'&matchmatchid='.$MatchingMatchId.'">'.$MatchingMatchStatusIcon.'<sub>'.$WinningsRobotVs."</sub>/<sup>".$WinningsRobot.'</sup></a>';
													if (($MatchRobotId == $tRobotInner->id) && ($MatchRobotVsId == $tRobot->id)) {
														$tmpString = '<td class="icon">'.$MatchingMatchStatusIcon.'</td>'.'<td>'.'<a class="selected" href="?page=scoreadministration&event='.$EventId.'&tournament='.$TournamentId.'&game='.$GameId.'&matchrobotid='.$tRobotInner->id.'&matchrobotvsid='.$tRobot->id.'&matchmatchid='.$MatchingMatchId.'">'.$tRobotInner->Name.' VS '.$tRobot->Name.'</a>'.'</td>';
													} else {
														$tmpString = '<td class="icon">'.$MatchingMatchStatusIcon.'</td>'.'<td>'.'<a href="?page=scoreadministration&event='.$EventId.'&tournament='.$TournamentId.'&game='.$GameId.'&matchrobotid='.$tRobotInner->id.'&matchrobotvsid='.$tRobot->id.'&matchmatchid='.$MatchingMatchId.'">'.$tRobotInner->Name.' VS '.$tRobot->Name.'</a>'.'</td>';
													}
													if ($MatchingMatchStatus == 3) {
														$oListMatchesDone->addItem($tmpString);
													} else {
														$oListMatchesTobe->addItem($tmpString);
													}
												} else {
													echo '<a href="?page=scoreadministration&event='.$EventId.'&tournament='.$TournamentId.'&game='.$GameId.'&matchrobotid='.$tRobotInner->id.'&matchrobotvsid='.$tRobot->id.'&matchmatchid=new">'.$MatchingMatchStatusIcon.'Na</a>';
													if (($MatchRobotId == $tRobotInner->id) && ($MatchRobotVsId == $tRobot->id)) {
														$tmpString = '<td class="icon">'.$MatchingMatchStatusIcon.'</td>'.'<td>'.'<a class="selected" href="?page=scoreadministration&event='.$EventId.'&tournament='.$TournamentId.'&game='.$GameId.'&matchrobotid='.$tRobotInner->id.'&matchrobotvsid='.$tRobot->id.'&matchmatchid=new">'.$tRobotInner->Name.' VS '.$tRobot->Name.'</a>'.'</td>';
													} else {
														$tmpString = '<td class="icon">'.$MatchingMatchStatusIcon.'</td>'.'<td>'.'<a href="?page=scoreadministration&event='.$EventId.'&tournament='.$TournamentId.'&game='.$GameId.'&matchrobotid='.$tRobotInner->id.'&matchrobotvsid='.$tRobot->id.'&matchmatchid=new">'.$tRobotInner->Name.' VS '.$tRobot->Name.'</a>'.'</td>';
													}
													$oListMatchesTobe->addItem($tmpString);
												}
												/*echo $tmpString;*/
												
												echo "</td>";
											} else {
												echo '<td class="disabled">&nbsp;</td>';
											}
											$iRobotCounterInner += 1;
										}
										echo "</tr>";
										$iRobotCounterOuter += 1;
									}
									?></table>
									
									<div id="MatchesLeft">
									<span>Matches played, and left</span>
									<div id="MatchesLeftSub">
									<table>
									<?php
									foreach ($oListMatchesDone as &$oMatchDone) {
										if (strpos($oMatchDone, "selected") !== false) {
											echo '<tr class="trselected" id="trselectedid">';
										} else {
											echo '<tr>';
										}
										echo $oMatchDone;
										echo '</tr>';
									}
									foreach ($oListMatchesTobe as &$oMatchTobe) {
										if (strpos($oMatchTobe, "selected") !== false) {
											echo '<tr class="trselected" id="trselectedid">';
										} else {
											echo '<tr>';
										}
										echo $oMatchTobe;
										echo '</tr>';
									}
									?>
									</table>
									</div>
									<p>
									<?php
									echo $oListMatchesDone->count() . ' played out of ' . ($oListMatchesDone->count()+$oListMatchesTobe->count()).' matches';
									?>
									</p>
									</div>
									<script type="text/javascript">
										/* Scroll to selected item in Matches-Left-List */
										document.getElementById('MatchesLeftSub').scrollTop = document.getElementById('trselectedid').offsetTop;
									</script>
									<?php 
									if (strlen($MatchMatchId) > 0) {
										echo '<div id="EnterResults">';
										if (is_numeric($MatchMatchId)) {
											//echo "VS ".$MatchMatchId;
											$oRobotId = new Robot();
											$oRobotId->LoadRobot($MatchRobotId);
											$oRobotVsId = new Robot();
											$oRobotVsId->LoadRobot($MatchRobotVsId);
											$oMatch = new MatchRoundRobin();
											$oMatch->LoadMatch($MatchMatchId);
											
											echo '<h3>'.$oRobotId->Name.' <span style="font-size: 1.2em;">-VS-</span> '.$oRobotVsId->Name.'<span style="font-size: 0.6em;"> ['.$MatchMatchId.']</span>'.'</h3>'; ?>
											<form action="<?php echo '?page=scoreadministration&event='.$EventId.'&tournament='.$TournamentId.'&game='.$GameId.'&matchrobotid='.$oRobotId->id.'&matchrobotvsid='.$oRobotVsId->id.'&matchmatchid='.$MatchMatchId; ?>" method="post">
												<span>Match started: </span><input type="text" name="matchstarted" class="dateinput" style="width: 200px;" value="<?php echo $oMatch->Started; ?>" /><br />
												<span>Match updated: </span><input type="text" name="matchupdated" class="dateinput" style="width: 200px;" value="<?php echo date("Y-m-d H:i:s"); ?>" /><br />
												<span>Winner in Round 1: </span><select name="matchwinnerround1" style="width: 200px;">
													<option value="-1">&nbsp;</option>
													<option value="<?php echo $oRobotId->id ?>" <?php if($oMatch->WinnerRound1 == $oRobotId->id){echo 'selected';}else{echo '';};?>><?php echo $oRobotId->Name; ?></option>
													<option value="0" <?php if($oMatch->WinnerRound1 == '0'){echo 'selected';}else{echo '';};?>>Oavgjort</option>
													<option value="<?php echo $oRobotVsId->id ?>" <?php if($oMatch->WinnerRound1 == $oRobotVsId->id){echo 'selected';}else{echo '';};?>><?php echo $oRobotVsId->Name; ?></option>
												</select><br />
												<span>Winner in Round 2: </span><select name="matchwinnerround2" style="width: 200px;">
													<option value="-1">&nbsp;</option>
													<option value="<?php echo $oRobotId->id ?>" <?php if($oMatch->WinnerRound2 == $oRobotId->id){echo 'selected';}else{echo '';};?>><?php echo $oRobotId->Name; ?></option>
													<option value="0" <?php if($oMatch->WinnerRound2 == '0'){echo 'selected';}else{echo '';};?>>Oavgjort</option>
													<option value="<?php echo $oRobotVsId->id ?>" <?php if($oMatch->WinnerRound2 == $oRobotVsId->id){echo 'selected';}else{echo '';};?>><?php echo $oRobotVsId->Name; ?></option>
												</select><br />
												<span>Winner in Round 3: </span><select name="matchwinnerround3" style="width: 200px;">
													<option value="-1">&nbsp;</option>
													<option value="<?php echo $oRobotId->id ?>" <?php if($oMatch->WinnerRound3 == $oRobotId->id){echo 'selected';}else{echo '';};?>><?php echo $oRobotId->Name; ?></option>
													<option value="0" <?php if($oMatch->WinnerRound3 == '0'){echo 'selected';}else{echo '';};?>>Oavgjort</option>
													<option value="<?php echo $oRobotVsId->id ?>" <?php if($oMatch->WinnerRound3 == $oRobotVsId->id){echo 'selected';}else{echo '';};?>><?php echo $oRobotVsId->Name; ?></option>
												</select><br />
												<span>Comments:</span><br /><textarea name="matchcomment" rows="9" /><?php echo $oMatch->Comment; ?></textarea><br />
												<br />
												<input type="hidden" name="matchid" value="<?php echo $oMatch->id; ?>" />
												<input type="submit" name="matchaction" value="Save" />
											</form>
										</div>
										<?php
										} else {
											$oRobotId = new Robot();
											$oRobotId->LoadRobot($MatchRobotId);
											$oRobotVsId = new Robot();
											$oRobotVsId->LoadRobot($MatchRobotVsId);
											
											echo '<h3>'.$oRobotId->Name." VS ".$oRobotVsId->Name.'</h3>'; ?>
											<form action="<?php echo '?page=scoreadministration&event='.$EventId.'&tournament='.$TournamentId.'&game='.$GameId.'&matchrobotid='.$oRobotId->id.'&matchrobotvsid='.$oRobotVsId->id.'&matchmatchid='.$MatchMatchId; ?>" method="post">
												<span>Match started: </span><input type="text" name="matchstarted" class="dateinput" style="width: 200px;" value="<?php echo date("Y-m-d H:i:s"); ?>" /><br />
												<span>Match updated: </span><input type="text" name="matchupdated" class="dateinput" style="width: 200px;" value="<?php echo date("Y-m-d H:i:s"); ?>" /><br />
												<span>Winner in Round 1: </span><select name="matchwinnerround1" style="width: 200px;">
													<option value="-1">&nbsp;</option>
													<option value="<?php echo $oRobotId->id ?>" ><?php echo $oRobotId->Name; ?></option>
													<option value="0" >Oavgjort</option>
													<option value="<?php echo $oRobotVsId->id ?>" ><?php echo $oRobotVsId->Name; ?></option>
												</select><br />
												<span>Winner in Round 2: </span><select name="matchwinnerround2" style="width: 200px;">
													<option value="-1">&nbsp;</option>
													<option value="<?php echo $oRobotId->id ?>" ><?php echo $oRobotId->Name; ?></option>
													<option value="0" >Oavgjort</option>
													<option value="<?php echo $oRobotVsId->id ?>" ><?php echo $oRobotVsId->Name; ?></option>
												</select><br />
												<span>Winner in Round 3: </span><select name="matchwinnerround3" style="width: 200px;">
													<option value="-1">&nbsp;</option>
													<option value="<?php echo $oRobotId->id ?>" ><?php echo $oRobotId->Name; ?></option>
													<option value="0" >Oavgjort</option>
													<option value="<?php echo $oRobotVsId->id ?>" ><?php echo $oRobotVsId->Name; ?></option>
												</select><br />
												<span>Comments:</span><br /><textarea name="matchcomment" rows="9" /></textarea><br />
												<br />
												<input type="hidden" name="matchid" value="<?php echo $MatchMatchId; ?>" />
												<input type="submit" name="matchaction" value="Save" />
											</form>
										</div>
										<?php
										}
									}
									
									/* List score-table */
									echo '<div id="ScoreTableHolder">';
									$oRobotsScoreTable = new Collection();
									foreach ($tGame->Matches as &$tMatch) {
										/*echo $tMatch->Robotid.' '.$tMatch->RobotVSid.'<br>';*/
										$tmpRobotScore = new RobotScore();
										if ($oRobotsScoreTable->exists($tMatch->Robotid)) {
											$tmpRobotScore = $oRobotsScoreTable->getItem($tMatch->Robotid);
										} else {
											$tmpRobotScore->id = $tMatch->Robotid;
											$tmpRobot = new Robot();
											$tmpRobot->LoadRobot($tmpRobotScore->id);
											$tmpRobotScore->Name = $tmpRobot->Name;
											$oRobotsScoreTable->addItem($tmpRobotScore, $tMatch->Robotid);
										}
										
										$tmpRobotVsScore = new RobotScore();
										if ($oRobotsScoreTable->exists($tMatch->RobotVSid)) {
											$tmpRobotVsScore = $oRobotsScoreTable->getItem($tMatch->RobotVSid);
										} else {
											$tmpRobotVsScore->id = $tMatch->RobotVSid;
											$tmpRobot = new Robot();
											$tmpRobot->LoadRobot($tmpRobotVsScore->id);
											$tmpRobotVsScore->Name = $tmpRobot->Name;
											$oRobotsScoreTable->addItem($tmpRobotVsScore, $tMatch->RobotVSid);
										}
										
										$WinningsRobot = 0;
										$WinningsRobotVs = 0;
										if ($tMatch->WinnerRound1 == $tmpRobotScore->id) {
											$WinningsRobot += 1;
											$tmpRobotScore->RoundsWon += 1;
											$tmpRobotVsScore->RoundsLost += 1;
										}
										if ($tMatch->WinnerRound1 == $tmpRobotVsScore->id) {
											$WinningsRobotVs += 1;
											$tmpRobotVsScore->RoundsWon += 1;
											$tmpRobotScore->RoundsLost += 1;
										}
										if ($tMatch->WinnerRound2 == $tmpRobotScore->id) {
											$WinningsRobot += 1;
											$tmpRobotScore->RoundsWon += 1;
											$tmpRobotVsScore->RoundsLost += 1;
										}
										if ($tMatch->WinnerRound2 == $tmpRobotVsScore->id) {
											$WinningsRobotVs += 1;
											$tmpRobotVsScore->RoundsWon += 1;
											$tmpRobotScore->RoundsLost += 1;
										}
										if ($tMatch->WinnerRound3 == $tmpRobotScore->id) {
											$WinningsRobot += 1;
											$tmpRobotScore->RoundsWon += 1;
											$tmpRobotVsScore->RoundsLost += 1;
										}
										if ($tMatch->WinnerRound3 == $tmpRobotVsScore->id) {
											$WinningsRobotVs += 1;
											$tmpRobotVsScore->RoundsWon += 1;
											$tmpRobotScore->RoundsLost += 1;
										}
										
										/* Make sure all rounds is over before deciding on a winner of the match*/
										if (($tMatch->WinnerRound1 >= 0) && ($tMatch->WinnerRound2 >= 0) && ($tMatch->WinnerRound3 >= 0)) {
											if ($WinningsRobot > $WinningsRobotVs) {
												/*echo 'ForstaVann: ';*/
												$tmpRobotScore->MatchesWon += 1;
												$tmpRobotVsScore->MatchesLost += 1;
											}
											if ($WinningsRobotVs > $WinningsRobot) {
												/*echo 'AndraVann: ';*/
												$tmpRobotVsScore->MatchesWon += 1;
												$tmpRobotScore->MatchesLost += 1;
											}
										}
										
										
										/*echo $tmpRobotScore->Name.'['.$tmpRobotScore->id.'] VS '.$tmpRobotVsScore->Name.'['.$tmpRobotVsScore->id.'] '.$WinningsRobot.'-'.$WinningsRobotVs.'<br>';*/
									}
									echo '<table id="ScoreTable" class="tablesorter" cellspacing="1">';
									echo '<thead><tr><th>id</th><th>Name</th><th>MatchesWon</th><th>MatchesLost</th><th>RoundsWon</th><th>RoundsLost</th></tr></thead>';
									echo '<tbody>';
									foreach ($oRobotsScoreTable as &$tRobotScore) {
										echo '<tr>';
										echo '<td>'.$tRobotScore->id.'</td>';
										$tmpRobot = new Robot();
										$tmpRobot->LoadRobot($tRobotScore->id);
										$tmpRobot->LoadTeam();
										if (strlen($tmpRobot->oTeam->Name) > 0) {
											echo '<td>'.$tRobotScore->Name.' <span class="roundrobinteamname">'.$tmpRobot->oTeam->Name.'</span>'.'</td>';
										} else {
											echo '<td>'.$tRobotScore->Name.'</td>';
										}
										echo '<td>'.$tRobotScore->MatchesWon.'</td>';
										echo '<td>'.$tRobotScore->MatchesLost.'</td>';
										echo '<td>'.$tRobotScore->RoundsWon.'</td>';
										echo '<td>'.$tRobotScore->RoundsLost.'</td>';
										echo '</tr>';
									}
									echo '</tbody>';
									echo '</table>';
									echo '</div>';
									
									
									break;
								case 'doubleelimination':
									$tGame->LoadRobots();
									if ($_POST['doubleeliminationaction'] == "Save") {
										if (strlen($sTemp = $_POST['hiddenjson']) > 20) {
											$sTemp = $_POST['hiddenjson'];
											$sTemp = substr($sTemp, strpos($sTemp, "results")+9, -1);
											$tGame->sJson = $sTemp;
											$tGame->SaveGame();
											/*echo '<pre>'.$tGame->sJson.'</pre>';*/
										}
									}
									?>
									<!-- Double-Elemination start of Winners bracket -->
									<div id ="save">
										<div class="demo">
										</div>
										<form action="<?php echo '?page=scoreadministration&event='.$EventId.'&tournament='.$TournamentId.'&game='.$GameId; ?>" method="post">
												<input type="hidden" name="hiddenjson" id="hiddenjson" value="" />
												<input type="submit" name="doubleeliminationaction" value="Save" />											
										</form>
									</div>
<script type="text/javascript">
  var saveData = {
      teams : [ <?php
        /*["Team 1", "Team 2"], /*   /* first matchup */
        /*["Team 3", "Team 4"]  /*  /* second matchup */
		$iSecondCounter = 0;
		$sTeams = "";
		foreach ($tGame->Robots as &$tRobot) {
			$iSecondCounter += 1;
			if ($iSecondCounter <= 1) {
				$sTeams = $sTeams.'["'.$tRobot->Name.'", ';
			} else {
				$sTeams = $sTeams.'"'.$tRobot->Name.'"], ';
				$iSecondCounter = 0;
			}
		}
		echo substr($sTeams, 0, -2);
		?>
      ],
	  <?php
      /*results : [[1,0], [2,7]]*/
	  echo 'results : '.$tGame->sJson;
	  ?>
    }

  /* Called whenever bracket is modified
   *
   * data:     changed bracket object in format given to init
   * userData: optional data given when bracket is created.
   */
  function saveFn(data, userData) {
    var json = jQuery.toJSON(data)
	/*alert(json);*/
	$('#hiddenjson').val(json)
    /*$('#saveOutput').text('POST '+userData+' '+json)*/
    /* You probably want to do something like this
    jQuery.ajax("rest/"+userData, {contentType: 'application/json',
                                  dataType: 'json',
                                  type: 'post',
                                  data: json})
    */
  }

  $(function() {
      var container = $('div#save .demo')
      container.bracket({
        init: saveData,
        save: saveFn,
        userData: "http://myapi"})

      /* You can also inquiry the current data */
      var data = container.bracket('data')
      $('#dataOutput').text(jQuery.toJSON(data))
    })
  </script>
									<?php
									break;
								case 'scoreboard':
									$tGame->LoadRobots();
									$tGame->LoadMatches();
									$tGame->LoadColumns();
									echo '<div id="ScoreboardDiv">';
									echo '<table id="ScoreboardTable" border="1">';
									echo '<thead><tr><th>Robots</th>';
									foreach ($tGame->ScoreboardsColumns as &$tColumn) {
										echo '<th>';
										echo $tColumn->Name.' ['.$tColumn->id.']';
										echo '</th>';
									}
									echo '</tr></thead>';
									echo '<tbody>'."\r\n";
									foreach ($tGame->Robots as &$tRobot) {
										echo '<tr>';
										echo '<td>';
										echo $tRobot->Name.' ['.$tRobot->id.']';
										echo '</td>';
										foreach ($tGame->ScoreboardsColumns as &$tColumn) {
											$temp = '';
											foreach ($tGame->Matches as &$tmatch) {
												if (($tmatch->Robotid == $tRobot->id) && ($tmatch->Roundid == $tColumn->id)) {
													$temp = '<a href="?page=scoreadministration&event='.$EventId.'&tournament='.$TournamentId.'&game='.$GameId.'&matchrobotid='.$tRobot->id.'&matchrobotvsid='.$tColumn->id.'&matchmatchid='.$tmatch->id.'">'.$tmatch->Score.'</a>';
												}
											}
											if (strlen($temp) > 1) {
												echo '<td>';
												echo $temp;
												echo '</td>';
											} else {
												echo '<td>';
												echo '<a href="?page=scoreadministration&event='.$EventId.'&tournament='.$TournamentId.'&game='.$GameId.'&matchrobotid='.$tRobot->id.'&matchrobotvsid='.$tColumn->id.'&matchmatchid=new">n/a</a>';
												echo '</td>';
											}
										}
										echo '</tr>'."\r\n";
									}
									echo '</tbody>';
									echo '</table>';
									echo '</div>';
									if (strlen($MatchMatchId) > 0) {
										echo '<div id="EnterResults">';
										if (is_numeric($MatchMatchId)) {
											//echo "VS ".$MatchMatchId;
											$oRobotId = new Robot();
											$oRobotId->LoadRobot($MatchRobotId);
											$oRobotVsId = new ColumnRound();
											$oRobotVsId->LoadColumn($MatchRobotVsId);
											$oMatch = new MatchScoreboard();
											$oMatch->LoadMatch($MatchMatchId);
											
											echo '<h3>'.$oRobotVsId->Name.' <span style="font-size: 1.2em;">-</span> '.$oRobotId->Name.'<span style="font-size: 0.6em;"> ['.$MatchMatchId.']</span>'.'</h3>'; ?>
											<form action="<?php echo '?page=scoreadministration&event='.$EventId.'&tournament='.$TournamentId.'&game='.$GameId.'&matchrobotid='.$oRobotId->id.'&matchrobotvsid='.$oRobotVsId->id.'&matchmatchid='.$MatchMatchId; ?>" method="post">
												<span>Match started: </span><input type="text" name="matchstarted" class="dateinput" style="width: 200px;" value="<?php echo $oMatch->Started; ?>" /><br />
												<span>Match updated: </span><input type="text" name="matchupdated" class="dateinput" style="width: 200px;" value="<?php echo date("Y-m-d H:i:s"); ?>" /><br />
												<span>Score:</span><br /><textarea name="matchscore" rows="9" /><?php echo $oMatch->Score; ?></textarea><br />
												<span>Comments:</span><br /><textarea name="matchcomment" rows="9" /><?php echo $oMatch->Comment; ?></textarea><br />
												<br />
												<input type="hidden" name="matchid" value="<?php echo $oMatch->id; ?>" />
												<input type="submit" name="matchaction" value="Save" />
											</form>
										</div>
										<?php
										} else {
											$oRobotId = new Robot();
											$oRobotId->LoadRobot($MatchRobotId);
											$oRobotVsId = new ColumnRound();
											$oRobotVsId->LoadColumn($MatchRobotVsId);
											
											echo '<h3>'.$oRobotVsId->Name.' <span style="font-size: 1.2em;">-</span> '.$oRobotId->Name.'<span style="font-size: 0.6em;"> [new]</span>'.'</h3>'; ?>
											<form action="<?php echo '?page=scoreadministration&event='.$EventId.'&tournament='.$TournamentId.'&game='.$GameId.'&matchrobotid='.$oRobotId->id.'&matchrobotvsid='.$oRobotVsId->id.'&matchmatchid='.$MatchMatchId; ?>" method="post">
												<span>Match started: </span><input type="text" name="matchstarted" class="dateinput" style="width: 200px;" value="<?php echo date("Y-m-d H:i:s"); ?>" /><br />
												<span>Match updated: </span><input type="text" name="matchupdated" class="dateinput" style="width: 200px;" value="<?php echo date("Y-m-d H:i:s"); ?>" /><br />
												<span>Score:</span><br /><textarea name="matchscore" rows="9" /><?php echo $oMatch->Score; ?></textarea><br />
												<span>Comments:</span><br /><textarea name="matchcomment" rows="9" /><?php echo $oMatch->Comment; ?></textarea><br />
												<br />
												<input type="hidden" name="matchid" value="<?php echo $MatchMatchId; ?>" />
												<input type="submit" name="matchaction" value="Save" />
											</form>
										</div>
										<?php
										}
									}
									
									break;
							}
							echo "<br />";
							//echo $tGame->Robots->getItem(2)->Name;
						}
						break;
				}
				//echo $NavigationLevel;
				?>
				<div class="nofloat"></div>
			</div>
			<div class="nofloat"></div>
			<div id ="footer" class="nofloat" style="padding-top: 30px;">
				<hr />
				<p>Support: Tim Gremalm 073-999 44 47 tim@gremalm.se</p>
			</div>
		</div>
	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js" type="text/javascript"></script>
	<script src="chosen.jquery.js" type="text/javascript"></script>
	<script src="docsupport/prism.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">
		var config = {
			'.chosen-select'           : {},
			'.chosen-select-deselect'  : {allow_single_deselect:true},
			'.chosen-select-no-single' : {disable_search_threshold:10},
			'.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
			'.chosen-select-width'     : {width:"95%"},
			'.chosen-select-search'     : {search_contains:true}
		}
		for (var selector in config) {
			$(selector).chosen(config[selector]);
		}
	</script>
	-->
	<?php } ?>
	</body>
</html>
<?php
mysql_close($con);
?>
