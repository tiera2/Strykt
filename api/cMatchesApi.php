<?php
/**
File content is described in following text document
http://www.football-data.co.uk/notes.txt
**/
class MatchesApi {

	public static function getMatches() {
		file_put_contents('E0.csv', fopen('http://www.football-data.co.uk/mmz4281/1516/E0.csv', 'r'));
		$csv = array_map('str_getcsv', file('E0.csv'));
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
		$matches = MatchesApi::getMatches();
		$returnArr = array();
		$matches = json_decode($matches);
		foreach($matches as $key => $value) {
			if($value->HomeTeam === $team || $value->AwayTeam === $team) {
				$returnArr[] = $value;
			}
		}
		return json_encode($returnArr);
	}
}
?>
