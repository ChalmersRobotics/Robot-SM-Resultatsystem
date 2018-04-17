<?php
class Event {
	public $Name = '';
	public $Created = '';
	public $id = 0;
	public $Tournaments = Collection;
	public $Robots = Collection;
	function LoadEvent($db, $id) {
		$query = sprintf("SELECT * FROM events WHERE id='%s'",
						mysqli_real_escape_string($db, $id));
		$result = mysqli_query($db, $query) or die();
		
		while ($row = mysqli_fetch_assoc($result)) {
			$this->id = $row['id'];
			$this->Name = $row['name'];
			$this->Created = $row['created'];
		}
	}
	function SaveEvent($db) {
		if ($this->id == 0) {	//Create new
			$query = sprintf("INSERT INTO events SET created='%s', name='%s'",
				mysqli_real_escape_string($db, $this->Created),
				mysqli_real_escape_string($db, $this->Name));
			mysqli_query($db, $query) or die();
		} else {				//Update existing
			$query = sprintf("UPDATE events SET created='%s', name='%s' WHERE id='%s'",
				mysqli_real_escape_string($db, $this->Created),
				mysqli_real_escape_string($db, $this->Name),
				mysqli_real_escape_string($db, $this->id));
			mysqli_query($db, $query) or die();
		}
	}
	function LoadTournaments($db) {
		$this->Tournaments = new Collection();
		$query = sprintf("SELECT tournaments.id, tournaments.name, tournaments.created FROM tournaments INNER JOIN rel_event_tournament ON tournaments.id=rel_event_tournament.tournament_id WHERE rel_event_tournament.event_id='%s'",
						mysqli_real_escape_string($db, $this->id));
		$result = mysqli_query($db, $query) or die();
		
		while ($row = mysqli_fetch_assoc($result)) {
			$tmpTournament = new Tournament();
			$tmpTournament->id = $row['id'];
			$tmpTournament->Name = $row['name'];
			$tmpTournament->Created = $row['created'];
			
			$this->Tournaments->addItem($tmpTournament);
		}
	}
	function LoadRobots($db) {
		$this->Robots = new Collection();
		$query = sprintf("SELECT robots.robot_id, robots.robot_name, robots.created, robots.robot_class, robots.robot_weight, robots.robot_width, robots.robot_depth, robots.robot_height, robots.robot_image, robots.robot_features, robots.robot_weighin FROM robots INNER JOIN rel_event_robot ON robots.robot_id=rel_event_robot.robot_id WHERE rel_event_robot.event_id='%s'",
						mysqli_real_escape_string($db, $this->id));
		$result = mysqli_query($db, $query) or die();
		
		while ($row = mysqli_fetch_assoc($result)) {
			$tmpRobot = new Robot();
			
			$tmpRobot->id = $row['robot_id'];
			$tmpRobot->Name = $row['robot_name'];
			$tmpRobot->Created = $row['created'];
			$tmpRobot->RobotClass = $row['robot_class'];
			switch ($tmpRobot->RobotClass) {
				case 1:
					$tmpRobot->RobotClassName = 'Minisumo';
					break;
				case 2:
					$tmpRobot->RobotClassName = 'Standardsumo';
					break;
				case 3:
					$tmpRobot->RobotClassName = 'Femkampsbot';
					break;
				case 4:
					$tmpRobot->RobotClassName = 'Folkracebot';
					break;
				default:
					$tmpRobot->RobotClassName = 'Na';
			}
			$tmpRobot->Weight = $row['robot_weight'];
			$tmpRobot->Width = $row['robot_width'];
			$tmpRobot->Depth = $row['robot_depth'];
			$tmpRobot->Height = $row['robot_height'];
			$tmpRobot->Image = $row['robot_image'];
			$tmpRobot->Background = $row['robot_features'];
			$tmpRobot->WeighinDate = $row['robot_weighin'];
			
			$this->Robots->addItem($tmpRobot);
		}
	}
}
function RelEventTournamentNew($db, $EventId, $TournamentId) {
	$query = sprintf("INSERT INTO rel_event_tournament SET event_id='%s', tournament_id='%s'",
		mysqli_real_escape_string($db, $EventId),
		mysqli_real_escape_string($db, $TournamentId));
	mysqli_query($db, $query) or die();
}
function RelEventTournamentUpdate($db, $OldEventId, $OldTournamentId, $NewEventId, $NewTournamentId) {
	$query = sprintf("UPDATE rel_event_tournament SET event_id='%s', tournament_id='%s' WHERE event_id='%s' AND tournament_id='%s'",
		mysqli_real_escape_string($db, $NewEventId),
		mysqli_real_escape_string($db, $NewTournamentId),
		mysqli_real_escape_string($db, $OldEventId),
		mysqli_real_escape_string($db, $OldTournamentId));
	mysqli_query($db, $query) or die();
}
function RelEventTournamentDelete($db, $EventId, $TournamentId) {
	$query = sprintf("DELETE FROM rel_event_tournament WHERE event_id='%s' AND tournament_id='%s'",
		mysqli_real_escape_string($db, $EventId),
		mysqli_real_escape_string($db, $TournamentId));
	mysqli_query($db, $query) or die();
}

class Tournament {	//Minisumo, Standardsumo, Femkamp etc.
	public $Name = '';
	public $Created = '';
	public $id = 0;
	public $Games = Collection;
	public $Robots = Collection;
	function LoadTournament($db, $id) {
		$query = sprintf("SELECT * FROM tournaments WHERE id='%s'",
						mysqli_real_escape_string($db, $id));
		$result = mysqli_query($db, $query) or die();
		
		while ($row = mysqli_fetch_assoc($result)) {
			$this->id = $row['id'];
			$this->Name = $row['name'];
			$this->Created = $row['created'];
		}
	}
	function SaveTournament($db) {
		if ($this->id == 0) {	//Create new
			$query = sprintf("INSERT INTO tournaments SET created='%s', name='%s'",
				mysqli_real_escape_string($db, $this->Created),
				mysqli_real_escape_string($db, $this->Name));
			mysqli_query($db, $query) or die();
		} else {				//Update existing
			$query = sprintf("UPDATE tournaments SET created='%s', name='%s' WHERE id='%s'",
				mysqli_real_escape_string($db, $this->Created),
				mysqli_real_escape_string($db, $this->Name),
				mysqli_real_escape_string($db, $this->id));
			mysqli_query($db, $query) or die();
		}
	}
	function LoadGames($db) {
		$this->Games = new Collection();
		$query = sprintf("SELECT games.id, games.name, games.created, games.gametype FROM games INNER JOIN rel_tournament_game ON games.id=rel_tournament_game.game_id WHERE rel_tournament_game.tournament_id='%s'",
						mysqli_real_escape_string($db, $this->id));
		$result = mysqli_query($db, $query) or die();
		
		while ($row = mysqli_fetch_assoc($result)) {
			$tmpGame = new Game();
			$tmpGame->id = $row['id'];
			$tmpGame->Name = $row['name'];
			$tmpGame->Created = $row['created'];
			$tmpGame->Gametype = $row['gametype'];
			
			$this->Games->addItem($tmpGame);
		}
	}
	function LoadRobots($db) {
		$this->Robots = new Collection();
		$query = sprintf("SELECT robots.robot_id, robots.robot_name, robots.created, robots.robot_class, robots.robot_weight, robots.robot_width, robots.robot_depth, robots.robot_height, robots.robot_image, robots.robot_features, robots.robot_weighin FROM robots INNER JOIN rel_game_robot ON robots.robot_id=rel_game_robot.robot_id INNER JOIN rel_tournament_game ON rel_game_robot.game_id=rel_tournament_game.game_id WHERE rel_tournament_game.tournament_id='%s'",
						mysqli_real_escape_string($db, $this->id));
		$result = mysqli_query($db, $query) or die();
		
		while ($row = mysqli_fetch_assoc($result)) {
			$tmpRobot = new Robot();
			
			$tmpRobot->id = $row['robot_id'];
			$tmpRobot->Name = $row['robot_name'];
			$tmpRobot->Created = $row['created'];
			$tmpRobot->RobotClass = $row['robot_class'];
			switch ($tmpRobot->RobotClass) {
				case 1:
					$tmpRobot->RobotClassName = 'Minisumo';
					break;
				case 2:
					$tmpRobot->RobotClassName = 'Standardsumo';
					break;
				case 3:
					$tmpRobot->RobotClassName = 'Femkampsbot';
					break;
				case 4:
					$tmpRobot->RobotClassName = 'Folkracebot';
					break;
				default:
					$tmpRobot->RobotClassName = 'Na';
			}
			$tmpRobot->Weight = $row['robot_weight'];
			$tmpRobot->Width = $row['robot_width'];
			$tmpRobot->Depth = $row['robot_depth'];
			$tmpRobot->Height = $row['robot_height'];
			$tmpRobot->Image = $row['robot_image'];
			$tmpRobot->Background = $row['robot_features'];
			$tmpRobot->WeighinDate = $row['robot_weighin'];
			
			$this->Robots->addItem($tmpRobot);
		}
	}
}
function RelTournamentGameNew($db, $TournamentId, $GameID) {
	$query = sprintf("INSERT INTO rel_tournament_game SET tournament_id='%s', game_id='%s'",
		mysqli_real_escape_string($db, $TournamentId),
		mysqli_real_escape_string($db, $GameID));
	mysqli_query($db, $query) or die();
}
function RelTournamentGameUpdate($db, $OldTournamentId, $OldGameId, $NewTournamentId, $NewGameId) {
	$query = sprintf("UPDATE rel_tournament_game SET tournament_id='%s', game_id='%s' WHERE tournament_id='%s' AND game_id='%s'",
		mysqli_real_escape_string($db, $NewTournamentId),
		mysqli_real_escape_string($db, $NewGameId),
		mysqli_real_escape_string($db, $OldTournamentId),
		mysqli_real_escape_string($db, $OldGameId));
	mysqli_query($db, $query) or die();
}
function RelTournamentGameDelete($db, $TournamentId, $GameID) {
	$query = sprintf("DELETE FROM rel_tournament_game WHERE tournament_id='%s' AND game_id='%s'",
		mysqli_real_escape_string($db, $TournamentId),
		mysqli_real_escape_string($db, $GameID));
	mysqli_query($db, $query) or die();
}
function RelTournamentRobotNew($db, $TournamentId, $RobotID) {
	$query = sprintf("INSERT INTO rel_tournament_robot SET tournament_id='%s', robot_id='%s'",
		mysqli_real_escape_string($db, $TournamentId),
		mysqli_real_escape_string($db, $RobotID));
	mysqli_query($db, $query) or die();
}
function RelTournamentRobotUpdate($db, $OldTournamentId, $OldRobotId, $NewTournamentId, $NewRobotId) {
	$query = sprintf("UPDATE rel_tournament_robot SET tournament_id='%s', robot_id='%s' WHERE tournament_id='%s' AND robot_id='%s'",
		mysqli_real_escape_string($db, $NewTournamentId),
		mysqli_real_escape_string($db, $NewRobotId),
		mysqli_real_escape_string($db, $OldTournamentId),
		mysqli_real_escape_string($db, $OldRobotId));
	mysqli_query($db, $query) or die();
}
function RelTournamentRobotDelete($db, $TournamentId, $RobotID) {
	$query = sprintf("DELETE FROM rel_tournament_robot WHERE tournament_id='%s' AND robot_id='%s'",
		mysqli_real_escape_string($db, $TournamentId),
		mysqli_real_escape_string($db, $RobotID));
	mysqli_query($db, $query) or die();
}

class ColumnRound {
	public $Name = '';
	public $id = 0;
	public $Position = 0;
	public $Gameid = 0;
	function LoadColumn($db, $id) {
		$query = sprintf("SELECT * FROM scoreboardrounds WHERE id='%s'",
						mysqli_real_escape_string($db, $id));
		$result = mysqli_query($db, $query) or die();
		
		while ($row = mysqli_fetch_assoc($result)) {
			$this->id = $row['id'];
			$this->Gameid = $row['game_id'];
			$this->Name = $row['name'];
			$this->Position = $row['position'];
		}
	}
	function SaveColumn($db) {
		if ($this->id == 0) {	//Create new
			$query = sprintf("INSERT INTO scoreboardrounds SET game_id='%s', name='%s', position='%s'",
				mysqli_real_escape_string($db, $this->Gameid),
				mysqli_real_escape_string($db, $this->Name),
				mysqli_real_escape_string($db, $this->Position));
			mysqli_query($db, $query) or die();
		} else {				//Update existing
			$query = sprintf("UPDATE scoreboardrounds SET game_id='%s', name='%s', position='%s' WHERE id='%s'",
				mysqli_real_escape_string($db, $this->Gameid),
				mysqli_real_escape_string($db, $this->Name),
				mysqli_real_escape_string($db, $this->Position),
				mysqli_real_escape_string($db, $this->id));
			mysqli_query($db, $query) or die();
		}
	}
}

class Game {		//Crosstable, RoundRobin, DoubleElemination
	public $Name = '';
	public $Created = '';
	public $id = 0;
	public $Gametype = '';
	public $sJson = '';
	public $Matches = Collection;
	public $Robots = Collection;
	public $ScoreboardsColumns = Collection;
	function LoadGame($db, $id) {
		$query = sprintf("SELECT * FROM games WHERE id='%s'",
						mysqli_real_escape_string($db, $id));
		$result = mysqli_query($db, $query) or die();
		
		while ($row = mysqli_fetch_assoc($result)) {
			$this->id = $row['id'];
			$this->Name = $row['name'];
			$this->Created = $row['created'];
			$this->Gametype = $row['gametype'];
			$this->sJson = $row['json'];
		}
		
		//The json-string can't be empty, else it will result in loaderror
		if (strlen($this->sJson) == 0) {
			$this->sJson = "[]";
		}
	}
	function SaveGame($db) {
		if ($this->id == 0) {	//Create new
			$query = sprintf("INSERT INTO games SET created='%s', name='%s', gametype='%s', json='%s'",
				mysqli_real_escape_string($db, $this->Created),
				mysqli_real_escape_string($db, $this->Name),
				mysqli_real_escape_string($db, $this->Gametype),
				mysqli_real_escape_string($db, $this->sJson));
			mysqli_query($db, $query) or die();
		} else {				//Update existing
			$query = sprintf("UPDATE games SET created='%s', name='%s', gametype='%s', json='%s' WHERE id='%s'",
				mysqli_real_escape_string($db, $this->Created),
				mysqli_real_escape_string($db, $this->Name),
				mysqli_real_escape_string($db, $this->Gametype),
				mysqli_real_escape_string($db, $this->sJson),
				mysqli_real_escape_string($db, $this->id));
			mysqli_query($db, $query) or die();
		}
	}
	function LoadColumns($db) {
		if ($this->Gametype == "scoreboard") {
			$this->ScoreboardsColumns = new Collection();
			$query = sprintf("SELECT * FROM scoreboardrounds WHERE game_id='%s' ORDER BY position",
							mysqli_real_escape_string($db, $this->id));
			$result = mysqli_query($db, $query) or die();
			
			while ($row = mysqli_fetch_assoc($result)) {
				$tmpColumn = new ColumnRound();
				$tmpColumn->id = $row['id'];
				$tmpColumn->Gameid = $row['game_id'];
				$tmpColumn->Name = $row['name'];
				$tmpColumn->Position = $row['position'];
				
				$this->ScoreboardsColumns->addItem($tmpColumn);
			}
		}
	}
	function LoadMatches($db) {
		if ($this->Gametype == "roundrobin") {
			$this->Matches = new Collection();
			$query = sprintf("SELECT matchroundrobin.id, matchroundrobin.gameid, matchroundrobin.robotid, matchroundrobin.robotvsid, matchroundrobin.started, matchroundrobin.updated, matchroundrobin.winnerround1, matchroundrobin.winnerround2, matchroundrobin.winnerround3, matchroundrobin.comment, matchroundrobin.match_order FROM matchroundrobin WHERE matchroundrobin.gameid='%s'",
							mysqli_real_escape_string($db, $this->id));
			$result = mysqli_query($db, $query) or die();
			
			while ($row = mysqli_fetch_assoc($result)) {
				$tmpMatchRoundRobin = new MatchRoundRobin();
				$tmpMatchRoundRobin->id = $row['id'];
				$tmpMatchRoundRobin->Gameid = $row['gameid'];
				$tmpMatchRoundRobin->Robotid = $row['robotid'];
				$tmpMatchRoundRobin->RobotVSid = $row['robotvsid'];
				$tmpMatchRoundRobin->Started = $row['started'];
				$tmpMatchRoundRobin->Updated = $row['updated'];
				$tmpMatchRoundRobin->WinnerRound1 = $row['winnerround1'];
				$tmpMatchRoundRobin->WinnerRound2 = $row['winnerround2'];
				$tmpMatchRoundRobin->WinnerRound3 = $row['winnerround3'];
				$tmpMatchRoundRobin->Comment = $row['comment'];
				$tmpMatchRoundRobin->MatchOrder = $row['match_order'];
				
				$this->Matches->addItem($tmpMatchRoundRobin);
			}
		}
		//Double elemination
		if ($this->Gametype == "doubleelimination") {
			$this->Matches = new Collection();
			$query = sprintf("SELECT matchdoubleelimination.id, matchdoubleelimination.gameid, matchdoubleelimination.robotid, matchdoubleelimination.robotvsid, matchdoubleelimination.started, matchdoubleelimination.updated, matchdoubleelimination.ended, matchdoubleelimination.winnerround1, matchdoubleelimination.winnerround2, matchdoubleelimination.winnerround3, matchdoubleelimination.comment, matchdoubleelimination.tablenumber, matchdoubleelimination.tableorder, matchdoubleelimination.tabelwinnerloser FROM matchdoubleelimination WHERE matchdoubleelimination.gameid='%s'",
							mysqli_real_escape_string($db, $this->id));
			$result = mysqli_query($db, $query) or die();
			
			while ($row = mysqli_fetch_assoc($result)) {
				$tmpMatchDoubleElimination = new MatchDoubleElimination();
				$tmpMatchDoubleElimination->id = $row['id'];
				$tmpMatchDoubleElimination->Gameid = $row['gameid'];
				$tmpMatchDoubleElimination->Robotid = $row['robotid'];
				$tmpMatchDoubleElimination->RobotVSid = $row['robotvsid'];
				$tmpMatchDoubleElimination->Started = $row['started'];
				$tmpMatchDoubleElimination->Updated = $row['updated'];
				$tmpMatchDoubleElimination->Ended = $row['ended'];
				$tmpMatchDoubleElimination->WinnerRound1 = $row['winnerround1'];
				$tmpMatchDoubleElimination->WinnerRound2 = $row['winnerround2'];
				$tmpMatchDoubleElimination->WinnerRound3 = $row['winnerround3'];
				$tmpMatchDoubleElimination->Comment = $row['comment'];
				$tmpMatchDoubleElimination->TableNumber = $row['tablenumber'];
				$tmpMatchDoubleElimination->TableOrder = $row['tableorder'];
				$tmpMatchDoubleElimination->TabelWinnerLoser = $row['tabelwinnerloser'];
				
				$this->Matches->addItem($tmpMatchDoubleElimination);
			}
		}
		//Scoreboard
		if ($this->Gametype == "scoreboard") {
			$this->Matches = new Collection();
			$query = sprintf("SELECT * FROM rel_game_scoreboard_matches WHERE gameid='%s'",
							mysqli_real_escape_string($db, $this->id));
			$result = mysqli_query($db, $query) or die();
			
			while ($row = mysqli_fetch_assoc($result)) {
				$tmpMatchScoreboard = new MatchScoreboard();
				$tmpMatchScoreboard->id = $row['id'];
				$tmpMatchScoreboard->Gameid = $row['gameid'];
				$tmpMatchScoreboard->Robotid = $row['robotid'];
				$tmpMatchScoreboard->Roundid = $row['roundid'];
				$tmpMatchScoreboard->Score = $row['score'];
				$tmpMatchScoreboard->Comments = $row['comments'];
				$tmpMatchScoreboard->Started = $row['started'];
				$tmpMatchScoreboard->Updated = $row['updated'];
				
				$this->Matches->addItem($tmpMatchScoreboard);
			}
		}
		//Other type of games
	}
	function LoadRobots($db) {
		if (($this->Gametype == "roundrobin") || ($this->Gametype == "scoreboard")) {
			$this->Robots = new Collection();
			$query = sprintf("SELECT robots.robot_id, robots.robot_name, robots.created, robots.robot_class, robots.robot_weight, robots.robot_width, robots.robot_depth, robots.robot_height, robots.robot_image, robots.robot_features, robots.robot_weighin FROM robots INNER JOIN rel_game_robot ON robots.robot_id=rel_game_robot.robot_id WHERE rel_game_robot.game_id='%s'",
							mysqli_real_escape_string($db, $this->id));
			$result = mysqli_query($db, $query) or die();
			
			$i = 0;
			while ($row = mysqli_fetch_assoc($result)) {
				$i += 1;
				$tmpRobot = new Robot();
				
				$tmpRobot->id = $row['robot_id'];
				$tmpRobot->Name = $row['robot_name'];
				$tmpRobot->Created = $row['created'];
				$tmpRobot->RobotClass = $row['robot_class'];
				switch ($tmpRobot->RobotClass) {
					case 1:
						$tmpRobot->RobotClassName = 'Minisumo';
						break;
					case 2:
						$tmpRobot->RobotClassName = 'Standardsumo';
						break;
					case 3:
						$tmpRobot->RobotClassName = 'Femkampsbot';
						break;
					case 4:
						$tmpRobot->RobotClassName = 'Folkracebot';
						break;
					default:
						$tmpRobot->RobotClassName = 'Na';
				}
				$tmpRobot->Weight = $row['robot_weight'];
				$tmpRobot->Width = $row['robot_width'];
				$tmpRobot->Depth = $row['robot_depth'];
				$tmpRobot->Height = $row['robot_height'];
				$tmpRobot->Image = $row['robot_image'];
				$tmpRobot->Background = $row['robot_features'];
				$tmpRobot->WeighinDate = $row['robot_weighin'];
				
				$this->Robots->addItem($tmpRobot, $i);
			}
		}
		if ($this->Gametype == "doubleelimination") {
			$this->Robots = new Collection();
			$query = sprintf("SELECT robots.robot_id, robots.robot_name, robots.created, robots.robot_class, robots.robot_weight, robots.robot_width, robots.robot_depth, robots.robot_height, robots.robot_image, robots.robot_features, robots.robot_weighin, rel_game_robot_doubleelemination.overunder, rel_game_robot_doubleelemination.position FROM robots INNER JOIN rel_game_robot_doubleelemination ON robots.robot_id=rel_game_robot_doubleelemination.robot_id WHERE rel_game_robot_doubleelemination.game_id='%s' ORDER BY rel_game_robot_doubleelemination.position, rel_game_robot_doubleelemination.overunder",
							mysqli_real_escape_string($db, $this->id));
			$result = mysqli_query($db, $query) or die();
			
			$i = 0;
			while ($row = mysqli_fetch_assoc($result)) {
				$i += 1;
				$tmpRobot = new Robot();
				
				$tmpRobot->id = $row['robot_id'];
				$tmpRobot->Name = $row['robot_name'];
				$tmpRobot->Created = $row['created'];
				$tmpRobot->RobotClass = $row['robot_class'];
				switch ($tmpRobot->RobotClass) {
					case 1:
						$tmpRobot->RobotClassName = 'Minisumo';
						break;
					case 2:
						$tmpRobot->RobotClassName = 'Standardsumo';
						break;
					case 3:
						$tmpRobot->RobotClassName = 'Femkampsbot';
						break;
					case 4:
						$tmpRobot->RobotClassName = 'Folkracebot';
						break;
					default:
						$tmpRobot->RobotClassName = 'Na';
				}
				$tmpRobot->Weight = $row['robot_weight'];
				$tmpRobot->Width = $row['robot_width'];
				$tmpRobot->Depth = $row['robot_depth'];
				$tmpRobot->Height = $row['robot_height'];
				$tmpRobot->Image = $row['robot_image'];
				$tmpRobot->Background = $row['robot_features'];
				$tmpRobot->WeighinDate = $row['robot_weighin'];
				$tmpRobot->DoubleEleminationUpperLower = $row['overunder'];
				$tmpRobot->DoubleEleminationOrder = $row['position'];
				
				$this->Robots->addItem($tmpRobot, $i);
			}
		}
	}
}
function RelGameRobotNew($db, $GameId, $RobotID) {
	$query = sprintf("INSERT INTO rel_game_robot SET game_id='%s', robot_id='%s'",
		mysqli_real_escape_string($db, $GameId),
		mysqli_real_escape_string($db, $RobotID));
	mysqli_query($db, $query) or die();
	echo $query;
}
function RelGameRobotUpdate($db, $OldGameId, $OldRobotId, $NewGameId, $NewRobotId) {
	$query = sprintf("UPDATE rel_game_robot SET game_id='%s', robot_id='%s' WHERE game_id='%s' AND robot_id='%s'",
		mysqli_real_escape_string($db, $NewGameId),
		mysqli_real_escape_string($db, $NewRobotId),
		mysqli_real_escape_string($db, $OldGameId),
		mysqli_real_escape_string($db, $OldRobotId));
	mysqli_query($db, $query) or die();
}
function RelGameRobotDelete($db, $GameId, $RobotID) {
	$query = sprintf("DELETE FROM rel_game_robot WHERE game_id='%s' AND robot_id='%s'",
		mysqli_real_escape_string($db, $GameId),
		mysqli_real_escape_string($db, $RobotID));
	mysqli_query($db, $query) or die();
}

function RelGameRobotDoubleEleminationNew($db, $GameId, $RobotID) {
	$query = sprintf("INSERT INTO rel_game_robot_doubleelemination SET game_id='%s', robot_id='%s'",
		mysqli_real_escape_string($db, $GameId),
		mysqli_real_escape_string($db, $RobotID));
	mysqli_query($db, $query) or die();
	echo $query;
}
function RelGameRobotDoubleEleminationUpdate($db, $OldGameId, $OldRobotId, $NewGameId, $NewRobotId) {
	$query = sprintf("UPDATE rel_game_robot_doubleelemination SET game_id='%s', robot_id='%s' WHERE game_id='%s' AND robot_id='%s'",
		mysqli_real_escape_string($db, $NewGameId),
		mysqli_real_escape_string($db, $NewRobotId),
		mysqli_real_escape_string($db, $OldGameId),
		mysqli_real_escape_string($db, $OldRobotId));
	mysqli_query($db, $query) or die();
}
function RelGameRobotDoubleEleminationDelete($db, $GameId, $RobotID) {
	$query = sprintf("DELETE FROM rel_game_robot_doubleelemination WHERE game_id='%s' AND robot_id='%s'",
		mysqli_real_escape_string($db, $GameId),
		mysqli_real_escape_string($db, $RobotID));
	mysqli_query($db, $query) or die();
}

class MatchRoundRobin {		//RobotA VS RobotB
	public $id = 0;
	public $Gameid = 0;
	public $Robotid = 0;
	public $RobotVSid = 0;
	public $Started = '';
	public $Updated = '';
	public $Ended = '';
	public $WinnerRound1 = 0;
	public $WinnerRound2 = 0;
	public $WinnerRound3 = 0;
	public $Comment = '';
	public $MatchOrder = '';
	public $oRobot = Robot;
	public $oRobotVS = Robot;
	public $oWinnerRound1 = Robot;
	public $oWinnerRound2 = Robot;
	public $oWinnerRound3 = Robot;
	function LoadMatch($db, $id) {
		$query = sprintf("SELECT * FROM matchroundrobin WHERE id='%s'",
						mysqli_real_escape_string($db, $id));
		$result = mysqli_query($db, $query) or die();
		
		while ($row = mysqli_fetch_assoc($result)) {
			$this->id = $row['id'];
			$this->Gameid = $row['gameid'];
			$this->Robotid = $row['robotid'];
			$this->RobotVSid = $row['robotvsid'];
			$this->Started = $row['started'];
			$this->Updated = $row['updated'];
			$this->WinnerRound1 = $row['winnerround1'];
			$this->WinnerRound2 = $row['winnerround2'];
			$this->WinnerRound3 = $row['winnerround3'];
			$this->Comment = $row['comment'];
		}
	}
	function SaveMatch($db) {
		if ($this->id == 0) {	//Create new
			$query = sprintf("INSERT INTO matchroundrobin SET gameid='%s', robotid='%s', robotvsid='%s', started='%s', updated='%s', winnerround1='%s', winnerround2='%s', winnerround3='%s', comment='%s'",
				mysqli_real_escape_string($db, $this->Gameid),
				mysqli_real_escape_string($db, $this->Robotid),
				mysqli_real_escape_string($db, $this->RobotVSid),
				mysqli_real_escape_string($db, $this->Started),
				mysqli_real_escape_string($db, $this->Updated),
				mysqli_real_escape_string($db, $this->WinnerRound1),
				mysqli_real_escape_string($db, $this->WinnerRound2),
				mysqli_real_escape_string($db, $this->WinnerRound3),
				mysqli_real_escape_string($db, $this->Comment));
			mysqli_query($db, $query) or die();
		} else {				//Update existing
			$query = sprintf("UPDATE matchroundrobin SET gameid='%s', robotid='%s', robotvsid='%s', started='%s', updated='%s', winnerround1='%s', winnerround2='%s', winnerround3='%s', comment='%s' WHERE id='%s'",
				mysqli_real_escape_string($db, $this->Gameid),
				mysqli_real_escape_string($db, $this->Robotid),
				mysqli_real_escape_string($db, $this->RobotVSid),
				mysqli_real_escape_string($db, $this->Started),
				mysqli_real_escape_string($db, $this->Updated),
				mysqli_real_escape_string($db, $this->WinnerRound1),
				mysqli_real_escape_string($db, $this->WinnerRound2),
				mysqli_real_escape_string($db, $this->WinnerRound3),
				mysqli_real_escape_string($db, $this->Comment),
				mysqli_real_escape_string($db, $this->id));
			mysqli_query($db, $query) or die();
		}
	}
	function LoadRobots($db) {
		//Robot
		$query = sprintf("SELECT * FROM robots WHERE robot_id='%s'",
						mysqli_real_escape_string($db, $this->Robotid));
		$result = mysqli_query($db, $query) or die();
		
		while ($row = mysqli_fetch_assoc($result)) {
			$tmpRobot = new Robot();
			
			$tmpRobot->id = $row['robot_id'];
			$tmpRobot->Name = $row['robot_name'];
			$tmpRobot->Created = $row['created'];
			$tmpRobot->RobotClass = $row['robot_class'];
			switch ($tmpRobot->RobotClass) {
				case 1:
					$tmpRobot->RobotClassName = 'Minisumo';
					break;
				case 2:
					$tmpRobot->RobotClassName = 'Standardsumo';
					break;
				case 3:
					$tmpRobot->RobotClassName = 'Femkampsbot';
					break;
				case 4:
					$tmpRobot->RobotClassName = 'Folkracebot';
					break;
				default:
					$tmpRobot->RobotClassName = 'Na';
			}
			$tmpRobot->Weight = $row['robot_weight'];
			$tmpRobot->Width = $row['robot_width'];
			$tmpRobot->Depth = $row['robot_depth'];
			$tmpRobot->Height = $row['robot_height'];
			$tmpRobot->Image = $row['robot_image'];
			$tmpRobot->Background = $row['robot_features'];
			$tmpRobot->WeighinDate = $row['robot_weighin'];
			
			$this->oRobot = $tmpRobot;
		}
		
		//RobotVS
		$query = sprintf("SELECT * FROM robots WHERE robot_id='%s'",
						mysqli_real_escape_string($db, $this->RobotVSid));
		$result = mysqli_query($db, $query) or die();
		
		while ($row = mysqli_fetch_assoc($result)) {
			$tmpRobot = new Robot();
			
			$tmpRobot->id = $row['robot_id'];
			$tmpRobot->Name = $row['robot_name'];
			$tmpRobot->Created = $row['created'];
			$tmpRobot->RobotClass = $row['robot_class'];
			switch ($tmpRobot->RobotClass) {
				case 1:
					$tmpRobot->RobotClassName = 'Minisumo';
					break;
				case 2:
					$tmpRobot->RobotClassName = 'Standardsumo';
					break;
				case 3:
					$tmpRobot->RobotClassName = 'Femkampsbot';
					break;
				case 4:
					$tmpRobot->RobotClassName = 'Folkracebot';
					break;
				default:
					$tmpRobot->RobotClassName = 'Na';
			}
			$tmpRobot->Weight = $row['robot_weight'];
			$tmpRobot->Width = $row['robot_width'];
			$tmpRobot->Depth = $row['robot_depth'];
			$tmpRobot->Height = $row['robot_height'];
			$tmpRobot->Image = $row['robot_image'];
			$tmpRobot->Background = $row['robot_features'];
			$tmpRobot->WeighinDate = $row['robot_weighin'];
			
			$this->oRobotVS = $tmpRobot;
		}
		
		//Winner Round 1
		$this->oWinnerRound1 = new Robot();
		switch ($this->WinnerRound1) {
			case $this->Robotid:
				$this->oWinnerRound1 = $this->oRobot;
				break;
			case $this->RobotVSid:
				$this->oWinnerRound1 = $this->oRobotVS;
				break;
			default:
				//$oWinnerRound1 = Nothing;
		}
		
		//Winner Round 2
		$this->oWinnerRound2 = new Robot();
		switch ($this->WinnerRound2) {
			case $this->Robotid:
				$this->oWinnerRound2 = $this->oRobot;
				break;
			case $this->RobotVSid:
				$this->oWinnerRound2 = $this->oRobotVS;
				break;
			default:
				//$oWinnerRound2 = Nothing;
		}
		
		//Winner Round 3
		$this->oWinnerRound3 = new Robot();
		switch ($this->WinnerRound3) {
			case $this->Robotid:
				$this->oWinnerRound3 = $this->oRobot;
				break;
			case $this->RobotVSid:
				$this->oWinnerRound3 = $this->oRobotVS;
				break;
			default:
				//$oWinnerRound3 = Nothing;
		}
	}
}
class MatchScoreboard {
	public $id = 0;
	public $Gameid = 0;
	public $Robotid = 0;
	public $Roundid = 0;
	public $Score = '';
	public $Comments = '';
	public $Started = '';
	public $Updated = '';
	function LoadMatch($db, $id) {
		$query = sprintf("SELECT * FROM rel_game_scoreboard_matches WHERE id='%s'",
						mysqli_real_escape_string($db, $id));
		$result = mysqli_query($db, $query) or die();
		
		while ($row = mysqli_fetch_assoc($result)) {
			$this->id = $row['id'];
			$this->Gameid = $row['gameid'];
			$this->Robotid = $row['robotid'];
			$this->Roundid = $row['roundid'];
			$this->Score = $row['score'];
			$this->Comments = $row['comments'];
			$this->Started = $row['started'];
			$this->Updated = $row['updated'];
		}
	}
	function SaveMatch($db) {
		if ($this->id == 0) {	//Create new
			$query = sprintf("INSERT INTO rel_game_scoreboard_matches SET gameid='%s', robotid='%s', roundid='%s', score='%s', comments='%s', started='%s', updated='%s'",
				mysqli_real_escape_string($db, $this->Gameid),
				mysqli_real_escape_string($db, $this->Robotid),
				mysqli_real_escape_string($db, $this->Roundid),
				mysqli_real_escape_string($db, $this->Score),
				mysqli_real_escape_string($db, $this->Comments),
				mysqli_real_escape_string($db, $this->Started),
				mysqli_real_escape_string($db, $this->Updated));
			mysqli_query($db, $query) or die();
		} else {				//Update existing
			$query = sprintf("UPDATE rel_game_scoreboard_matches SET gameid='%s', robotid='%s', roundid='%s', score='%s', comments='%s', started='%s', updated='%s' WHERE id='%s'",
				mysqli_real_escape_string($db, $this->Gameid),
				mysqli_real_escape_string($db, $this->Robotid),
				mysqli_real_escape_string($db, $this->Roundid),
				mysqli_real_escape_string($db, $this->Score),
				mysqli_real_escape_string($db, $this->Comments),
				mysqli_real_escape_string($db, $this->Started),
				mysqli_real_escape_string($db, $this->Updated),
				mysqli_real_escape_string($db, $this->id));
			mysqli_query($db, $query) or die();
		}
	}
}
class MatchDoubleElimination {
	public $id = 0;
	public $Gameid = 0;
	public $Robotid = 0;
	public $RobotVSid = 0;
	public $Started = '';
	public $Updated = '';
	public $Ended = '';
	public $WinnerRound1 = 0;
	public $WinnerRound2 = 0;
	public $WinnerRound3 = 0;
	public $Comment = '';
	public $TableNumber = '';
	public $TableOrder = '';
	public $TabelWinnerLoser = '';
	public $oRobot = Robot;
	public $oRobotVS = Robot;
	public $oWinnerRound1 = Robot;
	public $oWinnerRound2 = Robot;
	public $oWinnerRound3 = Robot;
	function LoadMatch($db, $id) {
		$query = sprintf("SELECT * FROM matchdoubleelimination WHERE id='%s'",
						mysqli_real_escape_string($db, $id));
		$result = mysqli_query($db, $query) or die();
		
		while ($row = mysqli_fetch_assoc($result)) {
			$this->id = $row['id'];
			$this->Gameid = $row['gameid'];
			$this->Robotid = $row['robotid'];
			$this->RobotVSid = $row['robotvsid'];
			$this->Started = $row['started'];
			$this->Updated = $row['updated'];
			$this->Ended = $row['ended'];
			$this->WinnerRound1 = $row['winnerround1'];
			$this->WinnerRound2 = $row['winnerround2'];
			$this->WinnerRound3 = $row['winnerround3'];
			$this->Comment = $row['comment'];
			$this->TableNumber = $row['tablenumber'];
			$this->TableOrder = $row['tableorder'];
			$this->TabelWinnerLoser = $row['tabelwinnerloser'];
		}
	}
	function SaveMatch($db) {
		if ($this->id == 0) {	//Create new
			$query = sprintf("INSERT INTO matchdoubleelimination SET gameid='%s', robotid='%s', robotvsid='%s', started='%s', updated='%s', ended='%s', winnerround1='%s', winnerround2='%s', winnerround3='%s', comment='%s', tablenumber='%s', tableorder='%s', tabelwinnerloser='%s'",
				mysqli_real_escape_string($db, $this->Gameid),
				mysqli_real_escape_string($db, $this->Robotid),
				mysqli_real_escape_string($db, $this->RobotVSid),
				mysqli_real_escape_string($db, $this->Started),
				mysqli_real_escape_string($db, $this->Updated),
				mysqli_real_escape_string($db, $this->Ended),
				mysqli_real_escape_string($db, $this->WinnerRound1),
				mysqli_real_escape_string($db, $this->WinnerRound2),
				mysqli_real_escape_string($db, $this->WinnerRound3),
				mysqli_real_escape_string($db, $this->Comment),
				mysqli_real_escape_string($db, $this->TableNumber),
				mysqli_real_escape_string($db, $this->TableOrder),
				mysqli_real_escape_string($db, $this->TabelWinnerLoser));
			mysqli_query($db, $query) or die();
		} else {				//Update existing
			$query = sprintf("UPDATE matchdoubleelimination SET gameid='%s', robotid='%s', robotvsid='%s', started='%s', updated='%s', ended='%s', winnerround1='%s', winnerround2='%s', winnerround3='%s', comment='%s', tablenumber='%s', tableorder='%s' WHERE id='%s'",
				mysqli_real_escape_string($db, $this->Gameid),
				mysqli_real_escape_string($db, $this->Robotid),
				mysqli_real_escape_string($db, $this->RobotVSid),
				mysqli_real_escape_string($db, $this->Started),
				mysqli_real_escape_string($db, $this->Updated),
				mysqli_real_escape_string($db, $this->Ended),
				mysqli_real_escape_string($db, $this->WinnerRound1),
				mysqli_real_escape_string($db, $this->WinnerRound2),
				mysqli_real_escape_string($db, $this->WinnerRound3),
				mysqli_real_escape_string($db, $this->Comment),
				mysqli_real_escape_string($db, $this->TableNumber),
				mysqli_real_escape_string($db, $this->TableOrder),
				mysqli_real_escape_string($db, $this->TabelWinnerLoser),
				mysqli_real_escape_string($db, $this->id));
			mysqli_query($db, $query) or die();
		}
	}
	function LoadRobots($db) {
		//Robot
		$query = sprintf("SELECT * FROM robots WHERE robot_id='%s'",
						mysqli_real_escape_string($db, $this->Robotid));
		$result = mysqli_query($db, $query) or die();
		
		while ($row = mysqli_fetch_assoc($result)) {
			$tmpRobot = new Robot();
			
			$tmpRobot->id = $row['robot_id'];
			$tmpRobot->Name = $row['robot_name'];
			$tmpRobot->Created = $row['created'];
			$tmpRobot->RobotClass = $row['robot_class'];
			switch ($tmpRobot->RobotClass) {
				case 1:
					$tmpRobot->RobotClassName = 'Minisumo';
					break;
				case 2:
					$tmpRobot->RobotClassName = 'Standardsumo';
					break;
				case 3:
					$tmpRobot->RobotClassName = 'Femkampsbot';
					break;
				case 4:
					$tmpRobot->RobotClassName = 'Folkracebot';
					break;
				default:
					$tmpRobot->RobotClassName = 'Na';
			}
			$tmpRobot->Weight = $row['robot_weight'];
			$tmpRobot->Width = $row['robot_width'];
			$tmpRobot->Depth = $row['robot_depth'];
			$tmpRobot->Height = $row['robot_height'];
			$tmpRobot->Image = $row['robot_image'];
			$tmpRobot->Background = $row['robot_features'];
			$tmpRobot->WeighinDate = $row['robot_weighin'];
			
			$this->oRobot = $tmpRobot;
		}
		
		//RobotVS
		$query = sprintf("SELECT * FROM robots WHERE robot_id='%s'",
						mysqli_real_escape_string($db, $this->RobotVSid));
		$result = mysqli_query($db, $query) or die();
		
		while ($row = mysqli_fetch_assoc($result)) {
			$tmpRobot = new Robot();
			
			$tmpRobot->id = $row['robot_id'];
			$tmpRobot->Name = $row['robot_name'];
			$tmpRobot->Created = $row['created'];
			$tmpRobot->RobotClass = $row['robot_class'];
			switch ($tmpRobot->RobotClass) {
				case 1:
					$tmpRobot->RobotClassName = 'Minisumo';
					break;
				case 2:
					$tmpRobot->RobotClassName = 'Standardsumo';
					break;
				case 3:
					$tmpRobot->RobotClassName = 'Femkampsbot';
					break;
				case 4:
					$tmpRobot->RobotClassName = 'Folkracebot';
					break;
				default:
					$tmpRobot->RobotClassName = 'Na';
			}
			$tmpRobot->Weight = $row['robot_weight'];
			$tmpRobot->Width = $row['robot_width'];
			$tmpRobot->Depth = $row['robot_depth'];
			$tmpRobot->Height = $row['robot_height'];
			$tmpRobot->Image = $row['robot_image'];
			$tmpRobot->Background = $row['robot_features'];
			$tmpRobot->WeighinDate = $row['robot_weighin'];
			
			$this->oRobotVS = $tmpRobot;
		}
		
		//Winner Round 1
		$this->oWinnerRound1 = new Robot();
		switch ($this->WinnerRound1) {
			case $this->Robotid:
				$this->oWinnerRound1 = $this->oRobot;
				break;
			case $this->RobotVSid:
				$this->oWinnerRound1 = $this->oRobotVS;
				break;
			default:
				//$oWinnerRound1 = Nothing;
		}
		
		//Winner Round 2
		$this->oWinnerRound2 = new Robot();
		switch ($this->WinnerRound2) {
			case $this->Robotid:
				$this->oWinnerRound2 = $this->oRobot;
				break;
			case $this->RobotVSid:
				$this->oWinnerRound2 = $this->oRobotVS;
				break;
			default:
				//$oWinnerRound2 = Nothing;
		}
		
		//Winner Round 3
		$this->oWinnerRound3 = new Robot();
		switch ($this->WinnerRound3) {
			case $this->Robotid:
				$this->oWinnerRound3 = $this->oRobot;
				break;
			case $this->RobotVSid:
				$this->oWinnerRound3 = $this->oRobotVS;
				break;
			default:
				//$oWinnerRound3 = Nothing;
		}
	}
}
class RobotScore {
	public $id = 0;
	public $Name = '';
	public $MatchesWon = 0;
	public $MatchesLost = 0;
	public $RoundsWon = 0;
	public $RoundsLost = 0;
}
class Robot {
	public $id = 0;
	public $Name = '';
	public $Created = '';
	public $RobotClass = 0;
	public $RobotClassName = '';
	public $Weight = '';
	public $Width = '';
	public $Depth = '';
	public $Height = '';
	public $Image = '';
	public $Background = '';
	public $WeighinDate = '';
	public $DoubleEleminationUpperLower = '';
	public $DoubleEleminationOrder = '';
	public $oTeam = Team;
	function LoadRobot($db, $id) {
		$query = sprintf("SELECT * FROM robots WHERE robot_id='%s'",
						mysqli_real_escape_string($db, $id));
		$result = mysqli_query($db, $query) or die();
		
		while ($row = mysqli_fetch_assoc($result)) {
			$this->id = $row['robot_id'];
			$this->Name = $row['robot_name'];
			$this->Created = $row['created'];
			$this->RobotClass = $row['robot_class'];
			switch ($this->RobotClass) {
				case 1:
					$this->RobotClassName = 'Minisumo';
					break;
				case 2:
					$this->RobotClassName = 'Standardsumo';
					break;
				case 3:
					$this->RobotClassName = 'Femkampsbot';
					break;
				case 4:
					$this->RobotClassName = 'Folkracebot';
					break;
				default:
					$this->RobotClassName = 'Na';
			}
			$this->Weight = $row['robot_weight'];
			$this->Width = $row['robot_width'];
			$this->Depth = $row['robot_depth'];
			$this->Height = $row['robot_height'];
			$this->Image = $row['robot_image'];
			$this->Background = $row['robot_features'];
			$this->WeighinDate = $row['robot_weighin'];
		}
	}
	function SaveRobot($db) {
		if ($this->id == 0) {	//Create new
			$query = sprintf("INSERT INTO robots SET robot_name='%s', created='%s', robot_class='%s', robot_weight='%s', robot_width='%s', robot_depth='%s', robot_height='%s', robot_image='%s', robot_features='%s', robot_weighin='%s'",
				mysqli_real_escape_string($db, $this->Name),
				mysqli_real_escape_string($db, $this->Created),
				mysqli_real_escape_string($db, $this->RobotClass),
				mysqli_real_escape_string($db, $this->Weight),
				mysqli_real_escape_string($db, $this->Width),
				mysqli_real_escape_string($db, $this->Depth),
				mysqli_real_escape_string($db, $this->Height),
				mysqli_real_escape_string($db, $this->Image),
				mysqli_real_escape_string($db, $this->Background),
				mysqli_real_escape_string($db, $this->WeighinDate));
			mysqli_query($db, $query) or die();
		} else {				//Update existing
			$query = sprintf("UPDATE robots SET robot_name='%s', created='%s', robot_class='%s', robot_weight='%s', robot_width='%s', robot_depth='%s', robot_height='%s', robot_image='%s', robot_features='%s', robot_weighin='%s' WHERE robot_id='%s'",
				mysqli_real_escape_string($db, $this->Name),
				mysqli_real_escape_string($db, $this->Created),
				mysqli_real_escape_string($db, $this->RobotClass),
				mysqli_real_escape_string($db, $this->Weight),
				mysqli_real_escape_string($db, $this->Width),
				mysqli_real_escape_string($db, $this->Depth),
				mysqli_real_escape_string($db, $this->Height),
				mysqli_real_escape_string($db, $this->Image),
				mysqli_real_escape_string($db, $this->Background),
				mysqli_real_escape_string($db, $this->WeighinDate),
				mysqli_real_escape_string($db, $this->id));
			mysqli_query($db, $query) or die();
		}
	}
	function LoadTeam($db) {
		$this->Matches = new Collection();
		$query = sprintf("SELECT teams.team_id, teams.team_name, teams.team_mail, teams.team_telephone, teams.team_city, teams.team_background, teams.created, teams.url, teams.organisation, teams.teamleaderid FROM teams INNER JOIN rel_robot_team ON teams.team_id=rel_robot_team.rel_team_id WHERE rel_robot_team.rel_robot_id='%s'",
						mysqli_real_escape_string($db, $this->id));
		$result = mysqli_query($db, $query) or die();
		
		while ($row = mysqli_fetch_assoc($result)) {
			$tmpTeam = new Team();
			$tmpTeam->id = $row['team_id'];
			$tmpTeam->Name = $row['team_name'];
			$tmpTeam->Mail = $row['team_mail'];
			$tmpTeam->Telephone = $row['team_telephone'];
			$tmpTeam->Created = $row['created'];
			$tmpTeam->URL = $row['url'];
			$tmpTeam->Organisation = $row['organisation'];
			$tmpTeam->City = $row['team_city'];
			$tmpTeam->Background = $row['team_background'];
			$tmpTeam->TeamleaderId = $row['teamleaderid'];
			$this->oTeam = $tmpTeam;
		}
	}
}
function RelRobotTeamNew($db, $RobotID, $TeamId) {
	$query = sprintf("INSERT INTO rel_robot_team SET rel_robot_id='%s', rel_team_id='%s'",
		mysqli_real_escape_string($db, $RobotID),
		mysqli_real_escape_string($db, $TeamId));
	mysqli_query($db, $query) or die();
}
function RelRobotTeamUpdate($db, $OldRobotId, $OldTeamId, $NewRobotId, $NewTeamId) {
	$query = sprintf("UPDATE rel_robot_team SET rel_robot_id='%s', rel_team_id='%s' WHERE rel_robot_id='%s' AND rel_team_id='%s'",
		mysqli_real_escape_string($db, $NewRobotId),
		mysqli_real_escape_string($db, $NewTeamId),
		mysqli_real_escape_string($db, $OldRobotId),
		mysqli_real_escape_string($db, $OldTeamId));
	mysqli_query($db, $query) or die();
}
function RelRobotTeamDelete($db, $RobotID, $TeamId) {
	$query = sprintf("DELETE FROM rel_robot_team WHERE rel_robot_id='%s' AND rel_team_id='%s'",
		mysqli_real_escape_string($db, $RobotID),
		mysqli_real_escape_string($db, $TeamId));
	mysqli_query($db, $query) or die();
}

class Team {
	public $id = 0;
	public $Name = '';
	public $Mail = '';
	public $Telephone = '';
	public $Created = '';
	public $URL = 0;
	public $Organisation = 0;
	public $City = 0;
	public $Background = 0;
	public $TeamleaderId = 0;
	public $Teamleader = Participant;
	public $Participants = Collection;
	function LoadTeam($db, $id) {
		$query = sprintf("SELECT * FROM teams WHERE team_id='%s'",
						mysqli_real_escape_string($db, $id));
		$result = mysqli_query($db, $query) or die();
		
		while ($row = mysqli_fetch_assoc($result)) {
			$this->id = $row['team_id'];
			$this->Name = $row['team_name'];
			$this->Mail = $row['team_mail'];
			$this->Telephone = $row['team_telephone'];
			$this->Created = $row['created'];
			$this->URL = $row['url'];
			$this->Organisation = $row['organisation'];
			$this->City = $row['team_city'];
			$this->Background = $row['team_background'];
			$this->TeamleaderId = $row['teamleaderid'];
		}
	}
	function SaveTeam($db) {
		if ($this->id == 0) {	//Create new
			$query = sprintf("INSERT INTO teams SET team_name='%s', team_mail='%s', team_telephone='%s', created='%s', url='%s', organisation='%s', team_city='%s', team_background='%s', teamleaderid='%s'",
				mysqli_real_escape_string($db, $this->Name),
				mysqli_real_escape_string($db, $this->Mail),
				mysqli_real_escape_string($db, $this->Telephone),
				mysqli_real_escape_string($db, $this->Created),
				mysqli_real_escape_string($db, $this->URL),
				mysqli_real_escape_string($db, $this->Organisation),
				mysqli_real_escape_string($db, $this->City),
				mysqli_real_escape_string($db, $this->Background),
				mysqli_real_escape_string($db, $this->TeamleaderId));
			mysqli_query($db, $query) or die();
		} else {				//Update existing
			$query = sprintf("UPDATE teams SET team_name='%s', team_mail='%s', team_telephone='%s', created='%s', url='%s', organisation='%s', team_city='%s', team_background='%s', teamleaderid='%s' WHERE team_id='%s'",
				mysqli_real_escape_string($db, $this->Name),
				mysqli_real_escape_string($db, $this->Mail),
				mysqli_real_escape_string($db, $this->Telephone),
				mysqli_real_escape_string($db, $this->Created),
				mysqli_real_escape_string($db, $this->URL),
				mysqli_real_escape_string($db, $this->Organisation),
				mysqli_real_escape_string($db, $this->City),
				mysqli_real_escape_string($db, $this->Background),
				mysqli_real_escape_string($db, $this->TeamleaderId),
				mysqli_real_escape_string($db, $this->id));
			mysqli_query($db, $query) or die();
		}
	}
	function LoadParticipants($db) {
		$this->Participants = new Collection();
		$query = sprintf("SELECT participants.participant_id, participants.participant_name, participants.created, participants.telephone, participants.mail FROM participants INNER JOIN rel_participant_team ON participants.participant_id=rel_participant_team.rel_participant_id WHERE rel_participant_team.rel_team_id='%s'",
						mysqli_real_escape_string($db, $this->id));
		$result = mysqli_query($db, $query) or die();
		
		while ($row = mysqli_fetch_assoc($result)) {
			$tmpParticipant = new Participant();
			$tmpParticipant->id = $row['participant_id'];
			$tmpParticipant->Name = $row['participant_name'];
			$tmpParticipant->Created = $row['created'];
			$tmpParticipant->Telephone = $row['telephone'];
			$tmpParticipant->Mail = $row['mail'];
			
			$this->Participants->addItem($tmpParticipant);
		}
		
		$tmpParticipant = new Participant();
		if ($this->TeamleaderId != 0) {
			$query = sprintf("SELECT * FROM participants WHERE participant_id='%s'",
							mysqli_real_escape_string($db, $this->TeamleaderId));
			$result = mysqli_query($db, $query) or die();
			
			while ($row = mysqli_fetch_assoc($result)) {
				$tmpParticipant->id = $row['participant_id'];
				$tmpParticipant->Name = $row['participant_name'];
				$tmpParticipant->Created = $row['created'];
				$tmpParticipant->Telephone = $row['telephone'];
				$tmpParticipant->Mail = $row['mail'];
				
			}
		}
		$this->Teamleader = $tmpParticipant;
	}
}
function RelTeamParticipantNew($db, $TeamId, $ParticipantId) {
	$query = sprintf("INSERT INTO rel_participant_team SET rel_team_id='%s', rel_participant_id='%s'",
		mysqli_real_escape_string($db, $TeamId),
		mysqli_real_escape_string($db, $ParticipantId));
	mysqli_query($db, $query) or die();
}
function RelTeamParticipantUpdate($db, $OldTeamId, $OldParticipantId, $NewTeamId, $NewParticipantId) {
	$query = sprintf("UPDATE rel_participant_team SET rel_team_id='%s', rel_participant_id='%s' WHERE rel_team_id='%s' AND rel_participant_id='%s'",
		mysqli_real_escape_string($db, $NewTeamId),
		mysqli_real_escape_string($db, $NewParticipantId),
		mysqli_real_escape_string($db, $OldTeamId),
		mysqli_real_escape_string($db, $OldParticipantId));
	mysqli_query($db, $query) or die();
}
function RelTeamParticipantDelete($db, $TeamId, $ParticipantId) {
	$query = sprintf("DELETE FROM rel_participant_team WHERE rel_team_id='%s' AND rel_participant_id='%s'",
		mysqli_real_escape_string($db, $TeamId),
		mysqli_real_escape_string($db, $ParticipantId));
	mysqli_query($db, $query) or die();
}

class Participant {
	public $id = 0;
	public $Name = '';
	public $Created = '';
	public $Telephone = 0;
	public $Mail = 0;
	function LoadParticipant($db, $id) {
		$query = sprintf("SELECT * FROM participants WHERE participant_id='%s'",
						mysqli_real_escape_string($db, $id));
		$result = mysqli_query($db, $query) or die();
		
		while ($row = mysqli_fetch_assoc($result)) {
			$this->id = $row['participant_id'];
			$this->Name = $row['participant_name'];
			$this->Created = $row['created'];
			$this->Telephone = $row['telephone'];
			$this->Mail = $row['mail'];
		}
	}
	function SaveParticipant($db) {
		if ($this->id == 0) {	//Create new
			$query = sprintf("INSERT INTO participants SET participant_name='%s', created='%s', telephone='%s', mail='%s'",
				mysqli_real_escape_string($db, $this->Name),
				mysqli_real_escape_string($db, $this->Created),
				mysqli_real_escape_string($db, $this->Telephone),
				mysqli_real_escape_string($db, $this->Mail));
			mysqli_query($db, $query) or die();
		} else {				//Update existing
			$query = sprintf("UPDATE participants SET participant_name='%s', created='%s', telephone='%s', mail='%s' WHERE participant_id='%s'",
				mysqli_real_escape_string($db, $this->Name),
				mysqli_real_escape_string($db, $this->Created),
				mysqli_real_escape_string($db, $this->Telephone),
				mysqli_real_escape_string($db, $this->Mail),
				mysqli_real_escape_string($db, $this->id));
			mysqli_query($db, $query) or die();
		}
	}
}

class ManyCollections {		//Collections of many kinds
	public $Events = Collection;
	public $Teams = Collection;
	public $Participants = Collection;
	public $Robots = Collection;
	public $Games = Collection;
	function LoadTournaments($db) {
		$query = "SELECT * FROM events";
		$result = mysqli_query($db, $query) or die();
		
		$this->Events = new Collection();
		while ($row = mysqli_fetch_assoc($result)) {
			$tmpEvent = new Event();
			$tmpEvent->id = $row['id'];
			$tmpEvent->Name = $row['name'];
			$tmpEvent->Created = $row['created'];
			
			$this->Events->addItem($tmpEvent);
		}
	}
	function LoadTeams($db) {
		$query = "SELECT * FROM teams ORDER BY team_name";
		$result = mysqli_query($db, $query) or die();
		
		$this->Teams = new Collection();
		while ($row = mysqli_fetch_assoc($result)) {
			$tmpTeam = new Team();
			$tmpTeam->id = $row['team_id'];
			$tmpTeam->Name = $row['team_name'];
			$tmpTeam->Mail = $row['team_mail'];
			$tmpTeam->Telephone = $row['team_telephone'];
			$tmpTeam->Created = $row['created'];
			$tmpTeam->URL = $row['url'];
			$tmpTeam->Organisation = $row['organisation'];
			$tmpTeam->City = $row['team_city'];
			$tmpTeam->Background = $row['team_background'];
			$tmpTeam->TeamleaderId = $row['teamleaderid'];
			
			$this->Teams->addItem($tmpTeam);
		}
	}
	function LoadParticipants($db) {
		$query = "SELECT * FROM participants ORDER BY participant_name";
		$result = mysqli_query($db, $query) or die();
		
		$this->Participants = new Collection();
		while ($row = mysqli_fetch_assoc($result)) {
			$tmpParticipant = new Participant();
			$tmpParticipant->id = $row['participant_id'];
			$tmpParticipant->Name = $row['participant_name'];
			$tmpParticipant->Created = $row['created'];
			$tmpParticipant->Telephone = $row['telephone'];
			$tmpParticipant->Mail = $row['mail'];
			
			$this->Participants->addItem($tmpParticipant);
		}
	}
	function LoadGames($db) {
		$query = "SELECT * FROM games ORDER BY name";
		$result = mysqli_query($db, $query) or die();
		
		$this->Games = new Collection();
		while ($row = mysqli_fetch_assoc($result)) {
			$tmpGame = new Game();
			$tmpGame->id = $row['id'];
			$tmpGame->Name = $row['name'];
			$tmpGame->Created = $row['created'];
			$tmpGame->Telephone = $row['gametype'];
			
			$this->Games->addItem($tmpGame);
		}
	}
	function LoadRobots($db) {
		$query = "SELECT * FROM robots ORDER BY robot_name";
		$result = mysqli_query($db, $query) or die();
		
		$this->Robots = new Collection();
		while ($row = mysqli_fetch_assoc($result)) {
			$tmpRobot = new Robot();
			$tmpRobot->id = $row['robot_id'];
			$tmpRobot->Name = $row['robot_name'];
			$tmpRobot->Created = $row['created'];
			$tmpRobot->RobotClass = $row['robot_class'];
			switch ($tmpRobot->RobotClass) {
				case 1:
					$tmpRobot->RobotClassName = 'Minisumo';
					break;
				case 2:
					$tmpRobot->RobotClassName = 'Standardsumo';
					break;
				case 3:
					$tmpRobot->RobotClassName = 'Femkampsbot';
					break;
				case 4:
					$tmpRobot->RobotClassName = 'Folkracebot';
					break;
				default:
					$tmpRobot->RobotClassName = 'Na';
			}
			$tmpRobot->Weight = $row['robot_weight'];
			$tmpRobot->Width = $row['robot_width'];
			$tmpRobot->Depth = $row['robot_depth'];
			$tmpRobot->Height = $row['robot_height'];
			$tmpRobot->Image = $row['robot_image'];
			$tmpRobot->Background = $row['robot_features'];
			$tmpRobot->WeighinDate = $row['robot_weighin'];
			
			$this->Robots->addItem($tmpRobot);
		}
	}
}


class Collection implements IteratorAggregate, Countable {
        /** hold the colleciton of items in an array */
        protected $_items = array();

        /**
         * Optionally accept an array of items to use for the collection, if provided
         * @params array $items (optional)
         */
        public function __construct($items = null)
        {
                if ($items !== null && is_array($items)) {
                        $this->_items = $items;
                }
        }

        /**
         * Function to satisfy the IteratorAggregate interface.  Sets an
         * ArrayIterator instance for the server list to allow this class to be
         * iterable like an array.
         */
        public function getIterator()
        {
                return new ArrayIterator($this->_items);
        }

        /**
         * Function to satisfy the Countable interface, returns a count of the
         * length of the collection
         * @return int the collection length
         */
        public function count()
        {
                return $this->length();
        }

        /**
         * Function to add an item to the Collection, optionally specifying
         * the key to access the item with.  Returns the item passed in for
         * continuing work.
         * @param $item the object to add
         * @param $key the accessor key (optional)
         * @return mixed the item
         */
        public function addItem($item, $key = null)
        {
                if($key !== null) {
                        $this->_items[$key] = $item;
                } else {
                        $this->_items[] = $item;
                }

                return $item;
        }

        /**
         * Remove an item from the Collection identified by it's key
         * @param $key the identifying key of the item to remove
         */
        public function removeItem($key)
        {
                if(isset($this->_items[$key])) {
                        unset($this->_items[$key]);
                } else {
                        throw new Exception("Invalid key $key specified.");
                }
        }

        /**
         * Retrieve an item from the collection as identified by its key
         * @param $key the identifying key of the item to remove
         * @return item identified by the key
         */
        public function getItem($key)
        {
                if(isset($this->_items[$key])) {
                        return $this->_items[$key];
                } else {
                        throw new Exception("Invalid key $key specified.");
                }
        }

        /**
         * Function to return the entire list of servers as an array
         * of Server objects
         * @return array
         */
        public function getAll()
        {
                return $this->_items;
        }

        /**
         * Return the list of keys to all objects in the collection
         * @return array an array of items
         */
        public function keys()
        {
                return array_keys($this->_items);
        }

        /**
         * Return the length of the collection of items
         * @return int the size of the collection
         */
        public function length()
        {
                return count($this->_items);
        }

        /**
         * Check if an item with the identified key exists in the Collection
         * @param $key the key of the item to check
         * @return bool if the item is in the Collection
         */
        public function exists($key)
        {
                return (isset($this->_items[$key]));
        }
}
?>
