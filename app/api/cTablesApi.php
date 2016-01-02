<?php
/**
File content is described in following text document
http://www.football-data.co.uk/notes.txt
**/
class TablesApi 
{
	/**
	* Get JSON-string with Premier League table
	* 
	*/
	static function getPremierLeagueTable() {
		$filename = 'tables.txt';
		$downloadFile = !file_exists($filename);
		if(!$downloadFile) {
			$fileDate = date('y-m-d', filemtime($filename));
			$today = date('y-m-d');
			$downloadFile = $fileDate !== $today;
		}
		if($downloadFile) {
			$uri = 'http://api.football-data.org/v1/soccerseasons/398/leagueTable';
			$reqPrefs['http']['method'] = 'GET';
			$stream_context = stream_context_create($reqPrefs);
			$content = file_get_contents($uri, false, $stream_context);
			file_put_contents($filename, $content);
		}
		$data = file_get_contents($filename);
		$fixtures = json_decode($data, true);
		$str = '';
		
		require 'app/lib/teamNameMapper.php';
		$retArr = array();
		foreach($fixtures['standing'] as $key=>$value) {
			$standing = array();
			$standing['teamName']		= getTeamName($value['teamName']);
			$standing['position']		= $value['position'];
			$standing['playedGames']	= $value['playedGames'];
			$standing['goals']			= $value['goals'];
			$standing['goalsAgainst']	= $value['goalsAgainst'];
			$standing['wins']			= $value['wins'];
			$standing['draws']			= $value['draws'];
			$standing['losses']			= $value['losses'];
			$standing['points']			= $value['points'];
				/**
			*	Fullösning på att Stryktipset och football-data har olika namn på lagen
			*/
			
			$retArr[] = $standing;
		}
		return json_encode($retArr);
	}
}
?>