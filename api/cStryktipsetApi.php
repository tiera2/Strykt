<?php

include_once('simple_html_dom.php');

class StryktipsetAPI {

	public static function getRow() {
		// Create DOM from URL or file
		$html = file_get_html("http://www.svt.se/svttext/tvu/pages/551.html");

		$str = "";
		$last_row = false;
		
		//Skapa en sträng matchnummer,lag1-lag2,res1-res2,tecken. Separera med //
		foreach($html->find('span[class=C]') as $element) {
			if(trim($element->plaintext) === '13.') {
				$last_row = true;
			}
			if(trim($element->plaintext) == "") {
			
			} else if(trim($element->plaintext) === '1.' && $last_row) {
				break;
			} else {
				//Separera med // om nummer med efterföljande punkt
				if(preg_match("^\d((\.)+)^", $element->plaintext)) {
					if(trim($element->plaintext) ==='1.') {
						$str .= str_replace('.','',trim($element->plaintext));
					} else {
						$str .= '\\' . str_replace('.','',trim($element->plaintext));
					}
				} else if(substr($element->plaintext, 0, 1) === '-') {
					$str .= trim($element->plaintext);
				} else {
					$str .= ',' . trim($element->plaintext);
				}
			}
		}
		
		$arr = explode("\\", $str);
		$rows = array();
		foreach($arr as $key=>$value) {
			$temp = explode(',', $value);
			$teams = explode('-', $temp[1]);
			$result = explode('-', $temp[2]);
			$row = array();
			$row['matchNo'] 	= $temp[0];
			$row['homeTeam'] 	= $teams[0];
			$row['awayTeam'] 	= $teams[1];
			$row['homeGoal'] 	= $result[0];
			$row['awayGoal'] 	= $result[1];
			$row['sign'] 		= $temp[3];
			$rows[] = $row;
		}
		return json_encode($rows);
	}
}
?>
