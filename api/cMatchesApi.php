<?php
/**
File content is described in following text document
http://www.football-data.co.uk/notes.txt
**/
class MatchesApi {

	public static function getCsv() {
		file_put_contents('E0.csv', fopen('http://www.football-data.co.uk/mmz4281/1516/E0.csv', 'r'));
		$csv = array_map('str_getcsv', file('E0.csv'));
		unlink('E0.csv');
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
				$game['Date']		= date("Y-m-d", strtotime($value[$csv_pos['Date']]));;
				$game['HomeTeam'] 	= $value[$csv_pos['HomeTeam']];
				$game['AwayTeam'] 	= $value[$csv_pos['AwayTeam']];
				$game['FTHG'] 		= intval($value[$csv_pos['FTHG']]);
				$game['FTAG']		= intval($value[$csv_pos['FTAG']]);
				$games[] = $game;
			}
		}
		return json_encode($games);
	}
}
?>
