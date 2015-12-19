<?php
/**
File content is described in following text document
http://www.football-data.co.uk/notes.txt
**/

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
			$game = new Game($value[$csv_pos['HomeTeam']], $value[$csv_pos['AwayTeam']]);
			$game->setScore($value[$csv_pos['FTHG']] . '-' . $value[$csv_pos['FTAG']]);
			$games[] = $game;
	}
}
print_r($games);

class Game 
{
	private $homeTeam, $awayTeam;
	private $homeScore, $awayScore;
	
	public function __construct($ht, $at) {
		$this->homeTeam = $ht;
		$this->awayTeam = $at;
	}
	
	function setScore($hg, $ag) {
		$this->homeScore = intval($hg);
		$this->awayScore = intval($ag);
	}
}


?>
