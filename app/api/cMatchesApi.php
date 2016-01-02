<?php
/**
File content is described in following text document
http://www.football-data.co.uk/notes.txt
**/
class MatchesApi 
{
	/**
	* Get JSON-string with all matches
	* 
	*/
	public static function getMatches() {
        $dataPath = "app/data/";
		$leagues = array('E0', 'E1');
		$seasons = array('1516', '1415', '1314', '1213');
		$games = array();
		foreach($leagues as $league) {
			foreach($seasons as $season) {
				$filename = $league . '_' . $season . '.csv';
				MatchesApi::loadFile($dataPath . $filename, $league, $season);
				$csv = array_map('str_getcsv', file($filename));
				$csv_pos = array();
				
				foreach($csv as $key => $value) {
					if($key == 0) {
						/* Get header positions */
						foreach($value as $headpos => $headname) {
							$csv_pos[$headname] = $headpos;
						}
					} else {
						$game = array();
						$game['Date']		= trim(DateTime::createFromFormat('d/m/y', $value[$csv_pos['Date']])->format('Y-m-d'));
						$game['League']		= $league;
						$game['Season']		= $season;
						$game['HomeTeam'] 	= trim($value[$csv_pos['HomeTeam']]);
						$game['AwayTeam'] 	= trim($value[$csv_pos['AwayTeam']]);
						$game['HomeGoals'] 	= intval(trim($value[$csv_pos['FTHG']]));
						$game['AwayGoals']	= intval(trim($value[$csv_pos['FTAG']]));
						$game['Winner'] 	= $game['HomeGoals'] > $game['AwayGoals'] 		? $game['HomeTeam']	: 
												($game['HomeGoals'] == $game['AwayGoals'] 	? 'Draw' 			:
												$game['AwayTeam']);
						$games[] = $game;
					}
				}
			}
		}
		
		//Sort by Date DESC
		foreach ($games as $key => $row) {
			$dates[$key]  = $row['Date'];
		}
		array_multisort($dates, SORT_DESC, $games);
		
		return json_encode($games);
	}
	
	/**
	* Get json-string with matches for the specified teams. If two teams are sent in
	* a json-string with matches between the two teams are returned.
	* 
	* @param string $team - Specified team(s). Separate team names with ,
	*/
	public static function getTeam($team, $limit=500) {
		if($limit == 0) $limit = 5000;
		$teams = explode(',', $team);
		$returnArr = array();
		//Control that max two teams are sent in
		if(count($teams)<3) {
			$twoTeams = count($teams) == 2;
			$matches = json_decode(MatchesApi::getMatches($limit), true);
			foreach($matches as $value) {
				if($twoTeams) {
					if($value['HomeTeam'] === $teams[0] && $value['AwayTeam'] === $teams[1] ||
						$value['HomeTeam'] === $teams[1] && $value['AwayTeam'] === $teams[0]) {
						$returnArr[] = $value;
					}
				} else {
					if($value['HomeTeam'] === $teams[0] || $value['AwayTeam'] === $teams[0]) {
						$returnArr[] = $value;
					}
				}
				if(count($returnArr) >= $limit) {
					break;
				}
			}
		}
		return json_encode($returnArr);
	}
	
	/**
	 * Load file from football-data if no new cached is available
	 *
	 * @param string $filename Name of the saved file
	 * @param string $league E0=PL, E1=Championship
	 * @param string $season ex. 1516, 1415
	 */
	private static function loadFile($filePath, $league, $season) {
		$downloadFile = !file_exists($filePath);
		if(!$downloadFile) {
			$fileDate = date('y-m-d', filemtime($filePath));
			$today = date('y-m-d');
			$downloadFile = $fileDate !== $today;
		}
		if($downloadFile) {
			file_put_contents($filePath, fopen("http://www.football-data.co.uk/mmz4281/$season/$league.csv", 'r'));
		}
	}
}