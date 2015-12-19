<?php
/**
File content is described in following text document
http://www.football-data.co.uk/notes.txt
**/

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
		$gam = array();
		$gam['HomeTeam'] 	= $value[$csv_pos['HomeTeam']];
		$gam['AwayTeam'] 	= $value[$csv_pos['AwayTeam']];
		$gam['FTHG'] 		= intval($value[$csv_pos['FTHG']]);
		$gam['FTAG']		= intval($value[$csv_pos['FTAG']]);
		$games[] = $gam;
	}
}
echo json_encode($games);



?>
