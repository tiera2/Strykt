<?php
/**
File content is described in following text document
http://www.football-data.co.uk/notes.txt
**/
class MatchesApi 
{
	public static function getMatches() {
		$filename = 'E0.csv';
		MatchesApi::loadFile($filename);
		$csv = array_map('str_getcsv', file($filename));
		$csv_pos = array();
		$games = array();

		foreach($csv as $key => $value) {
			if($key == 0) {
				/* Get header positions */
				foreach($value as $headpos => $headname) {
					$csv_pos[$headname] = $headpos;
				}
			} else {
				$game = array();
				$game['Date']		= DateTime::createFromFormat('d/m/y', $value[$csv_pos['Date']])->format('Y-m-d');
				$game['HomeTeam'] 	= $value[$csv_pos['HomeTeam']];
				$game['AwayTeam'] 	= $value[$csv_pos['AwayTeam']];
				$game['HomeGoals'] 	= intval($value[$csv_pos['FTHG']]);
				$game['AwayGoals']	= intval($value[$csv_pos['FTAG']]);
				$games[] = $game;
			}
		}
		return json_encode($games);
	}
	
	public static function getTeam($team) {
		$teams = explode(',', $team);
		$returnArr = array();
		//Control that max two teams are sent in
		if(count($teams)<3) {
			$twoTeams = count($teams) == 2;
			$matches = json_decode(MatchesApi::getMatches());
			foreach($matches as $key => $value) {
				if($twoTeams) {
					if($value->HomeTeam === $teams[0] && $value->AwayTeam === $teams[1] ||
						$value->HomeTeam === $teams[1] && $value->AwayTeam === $teams[0]) {
						$returnArr[] = $value;
					}
				} else {
					if($value->HomeTeam === $teams[0] || $value->AwayTeam === $teams[0]) {
						$returnArr[] = $value;
					}
				}
			}
		}
		return json_encode($returnArr);
	}
	
	/**
	 * Load file from football-data if no new cached is available
	 *
	 * @param string $filename Name of the saved file
	 */
	private static function loadFile($filename) {
		$downloadFile = !file_exists($filename);
		if(!$downloadFile) {
			$fileDate = date('y-m-d', filemtime($filename));
			$today = date('y-m-d');
			$downloadFile = $fileDate !== $today;
		}
		if($downloadFile) {
			file_put_contents($filename, fopen('http://www.football-data.co.uk/mmz4281/1516/E0.csv', 'r'));
		}
	}
}