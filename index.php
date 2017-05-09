<?php
date_default_timezone_set('Europe/Stockholm');

include 'db.php';

$con = mysql_connect($dbhost, $dbuser, $dbpass);
if (!$con)
{
  die('Could not connect: ' . mysql_error());
}

@mysql_select_db($dbdatabase) or die( "Unable to select database");
@require_once ('robotclasses.php');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html lang="sv" xml:lang="sv" xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Language" content="sv" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

		<meta name="language" content="sv" />
		<meta name="author" content="Tim Gremalm" />
		<meta name="generator" content="The Mind of Tim Gremalm" />

		<meta name="url" content="http://gremalm.se/robotsm/" />

		<meta name="title" content="Robot-SM 2016" />
		<meta name="keywords" content="Robot-SM" />
		<meta name="description" content="Robot-SM" />
		<meta name="robots" content="NOINDEX, NOFOLLOW" />

		<link rel="stylesheet" href="style3.css" type="text/css" />

		<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>

		<script type="text/javascript" src="jquery-ui-1.8.16.custom.min.js"></script>
		<link rel="stylesheet" type="text/css" href="jquery-ui-1.8.16.custom.css" />
		<script type="text/javascript" src="jquery.json-2.2.min.js"></script>
		<link rel="stylesheet" type="text/css" href="jquery.bracket.min.css" />
		<script type="text/javascript" src="jquery.bracket.min.js"></script>

		<link rel="stylesheet" href="theme.blue.css" />
		<script type="text/javascript" src="jquery.tablesorter.js"></script>
		<script type="text/javascript" src="jquery.tablesorter.pager.js"></script>

<script id="js">$(function() {

	// call the tablesorter plugin
	$("#ScoreTable").tablesorter({
		theme : 'blue',

		// sort on the first column and third column in ascending order
		sortList: [[2,1],[1,0]]
	});

});</script>

		<script type="text/javascript">
		//<![CDATA[
		function RefreshPageAfter() {
			/*alert(document.URL);*/
			location.reload(true);
		}
		//]]>
		</script>

		<title>Robot-SM 2016</title>
	</head>
		<?php
		$NavigationLevel = 0;
		$sArgEvent = '';
		$sArgTournament = '';
		$sArgGame = '';
		$sArgMode = '';
		$sArgNoAd = '';
		$sArgNoHeader = '';
		$sArgNoHeading = '';
		$sArgNoLogo = '';
		$sArgTimeRefresh = '';
		$sArgTvBackground = '';
		$sArgTvBackgroundColor = '';
		$sArgNoFooter = '';


		//Check navigation-event
		if (!isset($_GET['event']) || $_GET['event'] == NULL || $_GET['event'] == '') {

		} else {
			$NavigationLevel = 1;
			$sArgEvent = $_GET['event'];
			if (!isset($_GET['tournament']) || $_GET['tournament'] == NULL || $_GET['tournament'] == '') {

			} else {
				$NavigationLevel = 2;
				$sArgTournament = $_GET['tournament'];
				if (!isset($_GET['game']) || $_GET['game'] == NULL || $_GET['game'] == '') {

				} else {
					$NavigationLevel = 3;
					$sArgGame = $_GET['game'];
					if (!isset($_GET['mode']) || $_GET['mode'] == NULL || $_GET['mode'] == '') {
						$sArgMode = '0';
						$NavigationLevel = 4;
					} else {
						$sArgMode = $_GET['mode'];
						$NavigationLevel = 4;
					}
				}
			}
		}

		if (!isset($_GET['noad']) || $_GET['noad'] == NULL || $_GET['noad'] == '') {

		} else {
			$sArgNoAd = $_GET['noad'];
		}

		//Uncomment to disable sponsor-ads
		//$sArgNoAd = 1;
		$sSponsors = '';
		$sSponsors .= '<table id="sponsors">';
		$sSponsors .= '<tr>';
		$sSponsors .= '<td><img src="CatAB.png" /></td>';
		$sSponsors .= '<td><img src="Parker.png" /></td>';
		$sSponsors .= '</tr>';
		$sSponsors .= '</table>';

		if (!isset($_GET['noheader']) || $_GET['noheader'] == NULL || $_GET['noheader'] == '') {

		} else {
			$sArgNoHeader = $_GET['noheader'];
		}

		if (!isset($_GET['noheading']) || $_GET['noheading'] == NULL || $_GET['noheading'] == '') {

		} else {
			$sArgNoHeading = $_GET['noheading'];
		}

		if (!isset($_GET['timerefresh']) || $_GET['timerefresh'] == NULL || $_GET['timerefresh'] == '') {

		} else {
			$sArgTimeRefresh = $_GET['timerefresh'];
		}

		if (!isset($_GET['tvbackground']) || $_GET['tvbackground'] == NULL || $_GET['tvbackground'] == '') {

		} else {
			$sArgTvBackground = 'background: '.$_GET['tvbackground'].';';
			$sArgTvBackgroundColor = $_GET['tvbackground'];
		}

		if (!isset($_GET['nologo']) || $_GET['nologo'] == NULL || $_GET['nologo'] == '') {

		} else {
			$sArgNoLogo = $_GET['nologo'];
		}

		if (!isset($_GET['nofooter']) || $_GET['nofooter'] == NULL || $_GET['nofooter'] == '') {

		} else {
			$sArgNoFooter = $_GET['nofooter'];
		}

		if (!isset($_GET['tvdatorn']) || $_GET['tvdatorn'] == NULL || $_GET['tvdatorn'] == '') {

		} else {
			$sArgNoHeader = "1";
			//$sArgNoHeading = "1";
			$sArgTimeRefresh = "5000";
			$sArgNoFooter;
			$sArgTvBackground = "white";
			$sArgTvBackgroundColor = "white";
			//$sArgNoLogo = "1";
			$sArgNoFooter = "1";
		}
		/*echo 'NavigationLevel'.$NavigationLevel.'<br />';
		echo 'sArgEvent'.$sArgEvent.'<br />';
		echo 'sArgTournament'.$sArgTournament.'<br />';
		echo 'sArgGame'.$sArgGame.'<br />';
		echo 'sArgMode'.$sArgMode.'<br />';*/


		//<body>
		echo '<body style="'.$sArgTvBackground.'">'
		?>
		<div id="ground">
			<?php if ($sArgNoLogo != 1) { ?>
			<div id="logotype">
				<img src="robot-sm2016-logotype.png" />
			</div>
			<?php } ?>
			<?php if ($sArgNoHeader != 1) { ?>
			<div id="menucontainer">
				<div class="menulevel" id="menulevel1">
					<ul>
						<?php
						$oMany = new ManyCollections();
						$oMany->LoadTournaments();
						foreach ($oMany->Events as &$tEvent) { ?>
							<li class="<?php echo $sArgEvent == $tEvent->id ? 'currentmenu' : ''; ?>"><a href="?event=<?php echo $tEvent->id; ?>" title="<?php echo $tEvent->Name; ?>" alt ="<?php echo $tEvent->Name; ?>"><?php echo $tEvent->Name; ?></a></li>
						<?php
						}
						?>
					</ul>
					<span>&gt;</span>
				</div>
				<div class="nofloat">&nbsp;</div>

				<?php if ($NavigationLevel >= 1) { ?>
				<div class="menulevel" id="menulevel2">
					<ul>
						<?php
						$oEvent = new Event();
						$oEvent->LoadEvent($sArgEvent);
						$oEvent->LoadTournaments();
						foreach ($oEvent->Tournaments as &$tTournament) { ?>
							<li class="<?php echo $sArgTournament == $tTournament->id ? 'currentmenu' : ''; ?>"><a href="?event=<?php echo $sArgEvent; ?>&tournament=<?php echo $tTournament->id; ?>" title="<?php echo $tTournament->Name; ?>" alt ="<?php echo $tTournament->Name; ?>"><?php echo $tTournament->Name; ?></a></li>
						<?php
						}
						?>
					</ul>
					<span>&gt;</span>
				</div>
				<div class="nofloat">&nbsp;</div>
				<?php } ?>

				<?php if ($NavigationLevel >= 2) { ?>
				<div class="menulevel" id="menulevel3">
					<ul>
						<?php
						$oTournament = new Tournament();
						$oTournament->LoadTournament($sArgTournament);
						$oTournament->LoadGames();
						foreach ($oTournament->Games as &$tGame) { ?>
							<li class="<?php echo $sArgGame == $tGame->id ? 'currentmenu' : ''; ?>"><a href="?event=<?php echo $sArgEvent; ?>&tournament=<?php echo $sArgTournament; ?>&game=<?php echo $tGame->id; ?>" title="<?php echo $tGame->Name; ?>" alt ="<?php echo $tGame->Name; ?>"><?php echo $tGame->Name; ?></a></li>
						<?php
						}
						?>
					</ul>
					<span>&gt;</span>
				</div>
				<div class="nofloat">&nbsp;</div>
				<?php } ?>

				<?php if ($NavigationLevel >= 3) { ?>
				<div class="menulevel" id="menulevel4">
					<ul>
						<?php
						$oGame = new Game();
						$oGame->LoadGame($sArgGame);
						switch ($oGame->Gametype) {
							case 'roundrobin': ?>
								<li class="<?php echo $sArgMode == '1' ? 'currentmenu' : ''; ?>"><a href="?event=<?php echo $sArgEvent; ?>&tournament=<?php echo $sArgTournament; ?>&game=<?php echo $sArgGame; ?>&mode=1" title="Round-Robin - Winning arrows" alt ="Round-Robin - Winning arrows">Round-Robin - Winning arrows</a></li>
								<li class="<?php echo $sArgMode == '0' ? 'currentmenu' : ''; ?>"><a href="?event=<?php echo $sArgEvent; ?>&tournament=<?php echo $sArgTournament; ?>&game=<?php echo $sArgGame; ?>&mode=0" title="Round-Robin - Latest game" alt ="Round-Robin - Latest game">Round-Robin - Latest game</a></li>
								<li class="<?php echo $sArgMode == '2' ? 'currentmenu' : ''; ?>"><a href="?event=<?php echo $sArgEvent; ?>&tournament=<?php echo $sArgTournament; ?>&game=<?php echo $sArgGame; ?>&mode=2" title="Round-Robin - Statistics" alt ="Round-Robin - Statistics">Round-Robin - Statistics</a></li>
								<li class="<?php echo $sArgMode == '3' ? 'currentmenu' : ''; ?>"><a href="?event=<?php echo $sArgEvent; ?>&tournament=<?php echo $sArgTournament; ?>&game=<?php echo $sArgGame; ?>&mode=3" title="Round-Robin - VS" alt ="Round-Robin - VS">Round-Robin - VS</a></li>
								<?php
								break;
							case 'doubleelimination': ?>
								<li class="<?php echo $sArgMode == '0' ? 'currentmenu' : ''; ?>"><a href="?event=<?php echo $sArgEvent; ?>&tournament=<?php echo $sArgTournament; ?>&game=<?php echo $sArgGame; ?>&mode=0" title="Double Elemination Schematic" alt ="Double Elemination Schematic">Double Elemination Schematic</a></li>
								<?php
								break;
							case 'scoreboard': ?>
								<li class="<?php echo $sArgMode == '0' ? 'currentmenu' : ''; ?>"><a href="?event=<?php echo $sArgEvent; ?>&tournament=<?php echo $sArgTournament; ?>&game=<?php echo $sArgGame; ?>&mode=0" title="Scoreboard" alt ="Scoreboard">Scoreboard</a></li>
								<?php
								break;
							default: ?>
								<li class="<?php echo $sArgMode == '0' ? 'currentmenu' : ''; ?>"><a href="?event=<?php echo $sArgEvent; ?>&tournament=<?php echo $sArgTournament; ?>&game=<?php echo $sArgGame; ?>&mode=0" title="Default" alt ="Default">Default</a></li>
								<?php
								break;
						}
						?>
					</ul>
				</div>
				<div class="nofloat">&nbsp;</div>
				<?php } ?>
			</div>
			<?php } ?>
			<div class="nofloat"></div><?php
			if (strlen($sArgTvBackgroundColor) > 0) {
				echo '<div id="content" style="border: '.$sArgTvBackgroundColor.';">';
			} else {
				echo '<div id="content">';
			}	?>
				<?php
				if ($NavigationLevel >= 3) {
					$tGame = new Game();
					$tGame->LoadGame($sArgGame);
					switch ($tGame->Gametype) {
						case 'roundrobin':
							switch ($sArgMode) {
								/* Mode0=Round-Robin Table with arrows */
								case 1:
									$tGame->LoadMatches();
									$tGame->LoadRobots();
									if ($sArgNoHeading != 1) {
										echo '<h1>';
										echo $tGame->Name.' <span style="font-size:0.8em;">[Round Robin]</span>';
										echo '</h1>';
									}
									?><table border="1" id="RoundRobinTable" class="roundrobin">
									<tr class="roundrobinheader"><td>&nbsp;</td><?php
									foreach ($tGame->Robots as &$tRobot) {
										$tRobot->LoadTeam();
										if (strlen($tRobot->oTeam->Name) > 0) {
											echo '<td>'.$tRobot->Name.'<br /><span class="roundrobinteamname">'.$tRobot->oTeam->Name.'</span>'.'</td>';
										} else {
											echo '<td>'.$tRobot->Name.'</td>';
										}
									}
									?></tr><?php
									$iRobotCounterOuter = 0;
									foreach ($tGame->Robots as &$tRobot) {
										echo "\n<tr>";
										if (strlen($tRobot->oTeam->Name) > 0) {
											echo '<td class="roundrobinsecondaryheader">'.$tRobot->Name.'<br /><span class="roundrobinteamname">'.$tRobot->oTeam->Name.'</span>'.'</td>';
										} else {
											echo '<td class="roundrobinsecondaryheader">'.$tRobot->Name."</td>";
										}
										//Loop 2
										$iRobotCounterInner = 0;
										foreach ($tGame->Robots as &$tRobotInner) {
											if ($tRobot->id != $tRobotInner->id) {
												if ($iRobotCounterOuter < $iRobotCounterInner) {
													$Side = 'right';
												} else {
													$Side = 'left';
												}
												echo '<td class="enabled">';
												//Check if any match matches
												$MatchingMatchId = 0;
												$MatchingMatchStatus = 0;
												$MatchingMatchStatusIcon = "";
												foreach ($tGame->Matches as &$tMatch) {
													/*if ($tMatch->Robotid == $tRobotInner->id && $tMatch->RobotVSid == $tRobot->id) {*/
													if (($tMatch->Robotid == $tRobotInner->id && $tMatch->RobotVSid == $tRobot->id) || ($tMatch->RobotVSid == $tRobotInner->id && $tMatch->Robotid == $tRobot->id)) {
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

												/* Match was found when looking through matches */
												if ($MatchingMatchId != 0) {
													if ($MatchingMatchStatus == 1) {
														//Not started
														echo 'na';
													}
													if ($MatchingMatchStatus == 2) {
														//Started
														echo '<img src="timer.png" /><sub>'.$WinningsRobotVs."</sub>/<sup>".$WinningsRobot.'</sup>';
													}
													if ($MatchingMatchStatus == 3) {
														//Match done, show arrow

														//Check if a draw
														if ($WinningsRobot == $WinningsRobotVs) {
															echo '<img class="arrowimg" src="yellow-cross.png" /><sub>'.$WinningsRobotVs."</sub>/<sup>".$WinningsRobot.'</sup>';
														} else {
															if ($WinningsRobot > $WinningsRobotVs) {
																echo '<img class="arrowimg" src="red-arrow.png" /><sub>'.$WinningsRobotVs."</sub>/<sup>".$WinningsRobot.'</sup>';
															} else {
																echo '<img class="arrowimg" src="green-arrow.png" /><sub>'.$WinningsRobotVs."</sub>/<sup>".$WinningsRobot.'</sup>';
															}
														}
													}
												} else {
													//Not started
													echo 'na';
												}

												echo "</td>";
											} else {
												echo '<td class="disabled">&nbsp;</td>';
											}
											$iRobotCounterInner += 1;
										}
										echo "</tr>";
										$iRobotCounterOuter += 1;
									}
									echo '</table>';
									break;
								/* Mode1=Round-Robin - Latest game */
								case 0:
									$tGame->LoadMatches();
									$tGame->LoadRobots();
									if ($sArgNoHeading != 1) {
										echo '<h1>';
										echo $tGame->Name.' <span style="font-size:0.8em;">[Round Robin]</span>';
										echo '</h1>';
									}

									//Look for latest updated match
									$latestId = 0;
									$latestDate = DateTime::createFromFormat('Y-m-d H:i:s', "2000-01-01 00:00:00");
									foreach ($tGame->Matches as &$tMatch) {
										$tmpDate = DateTime::createFromFormat('Y-m-d H:i:s', $tMatch->Updated);
										$diffInSeconds = $latestDate->getTimestamp() - $tmpDate->getTimestamp();

										//Is this match newer?
										if ($diffInSeconds < 0) {
											$latestId = $tMatch->id;
											$latestDate = $tmpDate;
										}
									}
									$tLatestMatch = new MatchRoundRobin();
									$tLatestMatch->LoadMatch($latestId);
									//echo 'latest match '.$tLatestMatch->Robotid.' VS '.$tLatestMatch->RobotVSid;

									//Create the table that are showing the latest score
									?><table border="1" id="RoundRobinTable" class="roundrobin">
									<tr class="roundrobinheader"><td>&nbsp;</td><?php
									foreach ($tGame->Robots as &$tRobot) {
										if ($tLatestMatch->Robotid == $tRobot->id) {
											echo '<td class="headerselected">';
										} else {
											echo '<td>';
										}
										$tRobot->LoadTeam();
										if (strlen($tRobot->oTeam->Name) > 0) {
											echo $tRobot->Name.'<br /><span class="roundrobinteamname">'.$tRobot->oTeam->Name.'</span>'.'</td>';
										} else {
											echo $tRobot->Name.'</td>';
										}
									}
									?></tr><?php
									$iRobotCounterOuter = 0;
									foreach ($tGame->Robots as &$tRobot) {
										echo "\n<tr>";
										if ($tLatestMatch->RobotVSid == $tRobot->id) {
											echo '<td class="roundrobinsecondaryheader headersecondaryselected">';
										} else {
											echo '<td class="roundrobinsecondaryheader">';
										}
										if (strlen($tRobot->oTeam->Name) > 0) {
											echo $tRobot->Name.'<br /><span class="roundrobinteamname">'.$tRobot->oTeam->Name.'</span>'.'</td>';
										} else {
											echo $tRobot->Name.'</td>';
										}
										//Loop 2
										$iRobotCounterInner = 0;
										foreach ($tGame->Robots as &$tRobotInner) {
											if (($tRobot->id != $tRobotInner->id) && ($iRobotCounterOuter < $iRobotCounterInner)) {
												if (($tLatestMatch->Robotid == $tRobotInner->id) && ($tLatestMatch->RobotVSid == $tRobot->id)) {
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
												$PointToWinnerIcon = "";
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

															//Match done, show arrow
															//Check if a draw
															if ($WinningsRobot == $WinningsRobotVs) {
																$PointToWinnerIcon = '<img class="arrowimg" src="yellow-cross.png" /><sub>';
															} else {
																if ($WinningsRobot > $WinningsRobotVs) {
																	$PointToWinnerIcon = '<img class="arrowimg" src="red-arrow.png" />';
																} else {
																	$PointToWinnerIcon = '<img class="arrowimg" src="green-arrow.png" />';
																}
															}
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
													echo $MatchingMatchStatusIcon.'<sub>'.$WinningsRobotVs."</sub>/<sup>".$WinningsRobot.'</sup>'.' '.$PointToWinnerIcon;
												} else {
													echo $MatchingMatchStatusIcon.'Na';
												}

												echo "</td>";
											} else {
												echo '<td class="disabled">&nbsp;</td>';
											}
											$iRobotCounterInner += 1;
										}
										echo "</tr>";
										$iRobotCounterOuter += 1;
									}
									?></table><?php

									break;

								/* Mode2=Round-Robin - Statistics */
								case 2:
									$tGame->LoadMatches();
									$tGame->LoadRobots();
									if ($sArgNoHeading != 1) {
										echo '<h1>';
										echo $tGame->Name.' <span style="font-size:0.8em;">[Round Robin]</span>';
										echo '</h1>';
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
									echo '<thead><tr><th>Name</th><th>MatchesWon</th><th>MatchesLost</th><th>RoundsWon</th><th>RoundsLost</th></tr></thead>';
									echo '<tbody>';
									foreach ($oRobotsScoreTable as &$tRobotScore) {
										echo '<tr>';
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

								/* Mode3=Round-Robin - VS */
								case 3:
									$tGame->LoadMatches();
									$tGame->LoadRobots();
									if ($sArgNoHeading != 1) {
										echo '<h1>';
										echo $tGame->Name.' <span style="font-size:0.8em;">[Round Robin]</span>';
										echo '</h1>';
									}

									//Look for latest updated match
									$latestId = 0;
									$latestDate = DateTime::createFromFormat('Y-m-d H:i:s', "2000-01-01 00:00:00");
									foreach ($tGame->Matches as &$tMatch) {
										$tmpDate = DateTime::createFromFormat('Y-m-d H:i:s', $tMatch->Updated);
										$diffInSeconds = $latestDate->getTimestamp() - $tmpDate->getTimestamp();

										//Is this match newer?
										if ($diffInSeconds < 0) {
											$latestId = $tMatch->id;
											$latestDate = $tmpDate;
										}
									}
									$tLatestMatch = new MatchRoundRobin();
									$tLatestMatch->LoadMatch($latestId);
									//echo 'latest match '.$tLatestMatch->Robotid.' VS '.$tLatestMatch->RobotVSid;

									//Load latest match
									$tLatestMatch->LoadRobots();

									//Culculate score
									$MatchingMatchStatus = 0;
									$WinningsRobot = 0;
									$WinningsRobotVs = 0;
									if ($tLatestMatch->WinnerRound1 == $tLatestMatch->oRobot->id) {
										$WinningsRobot += 1;
									}
									if ($tLatestMatch->WinnerRound1 == $tLatestMatch->oRobotVS->id) {
										$WinningsRobotVs += 1;
									}
									if ($tLatestMatch->WinnerRound2 == $tLatestMatch->oRobot->id) {
										$WinningsRobot += 1;
									}
									if ($tLatestMatch->WinnerRound2 == $tLatestMatch->oRobotVS->id) {
										$WinningsRobotVs += 1;
									}
									if ($tLatestMatch->WinnerRound3 == $tLatestMatch->oRobot->id) {
										$WinningsRobot += 1;
									}
									if ($tLatestMatch->WinnerRound3 == $tLatestMatch->oRobotVS->id) {
										$WinningsRobotVs += 1;
									}
									if ($tLatestMatch->WinnerRound1 > -1) {
										/* Started */
										$MatchingMatchStatus = 2;
									} else {
										/* Not started */
										$MatchingMatchStatus = 1;
									}
									/* Finished */
									if ($tLatestMatch->WinnerRound3 > -1) {
										$MatchingMatchStatus = 3;
									}


									echo '<table id="RobotVsRobot">';

									//Finished
									if ($MatchingMatchStatus == 3) {
										echo '<tr class="FinishedHeader">';
										echo '<td></td>';
										echo '<td class="AlignCenter">Finished!</td>';
										echo '<td></td>';
										echo '</tr>';
									}

									//HeaderVS
									echo '<tr class="VsHeader">';
									echo '<td class="AlignRight">'.$tLatestMatch->oRobot->Name.'</td><td class="VsHeaderVS AlignCenter"> VS </td><td class="AlignLeft">'.$tLatestMatch->oRobotVS->Name.'</td>';
									echo '</tr>';

									//TeamHeaderVS
									$tLatestMatch->oRobot->LoadTeam();
									$tLatestMatch->oRobotVS->LoadTeam();
									echo '<tr class="TeamVsHeader">';
									echo '<td class="AlignRight">'.$tLatestMatch->oRobot->oTeam->Name.'</td><td></td><td class="AlignLeft">'.$tLatestMatch->oRobotVS->oTeam->Name.'</td>';
									echo '</tr>';

									//Score
									echo '<tr class="ScoreHeader">';
									echo '<td class="AlignRight">'.$WinningsRobot.'</td><td class="AlignCenter"> Score </td><td class="AlignLeft">'.$WinningsRobotVs.'</td>';
									echo '</tr>';

									//Image
									echo '<tr class="ImageHeader">';
									if (strlen($tLatestMatch->oRobot->Image) > 0 ) {
										echo '<td class="AlignRight"><img src="'.$tLatestMatch->oRobot->Image.'" /></td>';
									} else {
										echo '<td class="AlignRight"><img src="'.'robot.jpg'.'" /></td>';
									}
									echo '<td></td>';
									if (strlen($tLatestMatch->oRobotVS->Image) > 0 ) {
										echo '<td class="AlignLeft"><img src="'.$tLatestMatch->oRobotVS->Image.'" /></td>';
									} else {
										echo '<td class="AlignLeft"><img src="'.'robot.jpg'.'" /></td>';
									}
									echo '</tr>';

									//Info
									echo '<tr class="infoHeader">';
									echo '<td class="AlignRight">';
									echo $tLatestMatch->oRobot->oTeam->Organisation.'<br />';
									echo $tLatestMatch->oRobot->oTeam->City.'<br />';
									echo $tLatestMatch->oRobot->oTeam->URL.'<br />';
									if (!$tLatestMatch->oRobot->oTeam->Name == Null) {
										$tLatestMatch->oRobot->oTeam->LoadParticipants();
										foreach ($tLatestMatch->oRobot->oTeam->Participants as &$tParticipant) {
											echo $tParticipant->Name.'<br />';
										}
									}
									echo '<br />'.'Background & Specs:'.'<br />';
									echo $tLatestMatch->oRobot->Background.'<br />';
									echo '</td>';

									echo '<td></td>';

									echo '<td class="AlignLeft">';
									echo $tLatestMatch->oRobotVS->oTeam->Organisation.'<br />';
									echo $tLatestMatch->oRobotVS->oTeam->City.'<br />';
									echo $tLatestMatch->oRobotVS->oTeam->URL.'<br />';
									if (!$tLatestMatch->oRobotVS->oTeam->Name == Null) {
										$tLatestMatch->oRobotVS->oTeam->LoadParticipants();
										foreach ($tLatestMatch->oRobotVS->oTeam->Participants as &$tParticipant) {
											echo $tParticipant->Name.'<br />';
										}
									}
									echo '<br />'.'Background & Specs:'.'<br />';
									echo $tLatestMatch->oRobotVS->Background.'<br />';
									echo '</td>';

									echo '</tr>';

									echo '</table>';

									break;
							}
							break;
						case 'doubleelimination':
							$tGame->LoadRobots();
							if ($sArgNoHeading != 1) {
								echo '<h1>';
								echo $tGame->Name.' <span style="font-size:0.8em;">[Double Elimination]</span>';
								echo '</h1>';
							}
							?>
							<!-- Double-Elemination start of Winners bracket -->
							<div id ="save">
								<div class="demo">
								</div>
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

  $(function() {
      var container = $('div#save .demo')
      container.bracket({
        init: saveData
	  })
    })
  </script>
							<?php
							break;
						case 'scoreboard':
							if ($sArgNoHeading != 1) {
								echo '<h1>';
								echo $tGame->Name;
								echo '</h1>';
							}
							$tGame->LoadRobots();
							$tGame->LoadMatches();
							$tGame->LoadColumns();
							echo '<div id="ScoreboardDiv">';
							echo '<table id="ScoreboardTable" border="1">';
							echo '<thead><tr><th class="ScoreboardHeadertd">Robots</th>';
							foreach ($tGame->ScoreboardsColumns as &$tColumn) {
								echo '<th class="ScoreboardHeadertd">';
								echo $tColumn->Name;
								echo '</th>';
							}
							echo '</tr></thead>';
							echo '<tbody>'."\r\n";
							foreach ($tGame->Robots as &$tRobot) {
								echo '<tr>';
								echo '<td class="ScoreboardHeaderSecondarytd">';

								$tRobot->LoadTeam();
								if (strlen($tRobot->oTeam->Name) > 0) {
									echo $tRobot->Name.'<br /><span class="roundrobinteamname">'.$tRobot->oTeam->Name.'</span>';
								} else {
									echo $tRobot->Name;
								}

								echo '</td>';
								foreach ($tGame->ScoreboardsColumns as &$tColumn) {
									$temp = '';
									foreach ($tGame->Matches as &$tmatch) {
										if (($tmatch->Robotid == $tRobot->id) && ($tmatch->Roundid == $tColumn->id)) {
											$temp = $tmatch->Score;
										}
									}
									if (strlen($temp) > 0) {
										echo '<td>';
										echo $temp;
										echo '</td>';
									} else {
										echo '<td>';
										echo 'n/a';
										echo '</td>';
									}
								}
								echo '</tr>'."\r\n";
							}
							echo '</tbody>';
							echo '</table>';
							echo '</div>';
							break;
						default:
							echo 'Nothing to show';
							break;
					}
				}
				?>
				<div class="nofloat"></div>
				<?php
				if ($sArgNoAd != 1) {
					echo $sSponsors;
				}
				?>
			</div>
			<div class="nofloat"></div>
			<?php
			function getURLWithArgs($inArrayGet) {
				$out = "";
				$i = 0;
				foreach ($inArrayGet as $key => $value) {
					if ($i == 0) {
						$out = $out . "?";
					} else {
						$out = $out . "&";
					}
					$i = $i + 1;

					$out = $out . $key;
					$out = $out . "=";
					$out = $out . $value;
				}
				return $out;
			}
			if ($sArgNoFooter != 1) { ?>
			<div id ="footer" class="nofloat" style="padding-top: 30px;">
				<hr />
				<?php $getArgs = $_GET;
				if ($sArgTimeRefresh == 5000) {
					$getArgs["timerefresh"] = 0;
				} else {
					$getArgs["timerefresh"] = 5000;
				}
				echo '<a href="'.getURLWithArgs($getArgs).'">&#x1F551;</a>' ?>

				<?php $getArgs = $_GET;
				if ($sArgNoHeader == 1) {
					$getArgs["noheader"] = 0;
				} else {
					$getArgs["noheader"] = 1;
				}
				echo '<a href="'.getURLWithArgs($getArgs).'">&#x1F5B9;</a>' ?>

				<?php $getArgs = $_GET;
				if ($sArgNoAd == 1) {
					$getArgs["noad"] = 0;
				} else {
					$getArgs["noad"] = 1;
				}
				echo '<a href="'.getURLWithArgs($getArgs).'">&#x2122;</a>' ?>

				<?php $getArgs = $_GET;
				if ($sArgNoLogo == 1) {
					$getArgs["nologo"] = 0;
				} else {
					$getArgs["nologo"] = 1;
				}
				echo '<a href="'.getURLWithArgs($getArgs).'">&#x0F16;</a>' ?>

			</div>
			<?php }
			if ($sArgTimeRefresh > 0) { ?>
	        <script type="text/javascript">
            //<![CDATA[
	            setTimeout("RefreshPageAfter();", <?php echo $sArgTimeRefresh; ?>);
            //]]>
	        </script>
			<?php } ?>
		</div>
	</body>
</html>
<?php
mysql_close($con);
?>

