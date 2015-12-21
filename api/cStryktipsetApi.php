﻿<?php

include_once('simple_html_dom.php');

class StryktipsetAPI 
{
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
			$row['MatchNo'] 	= intval($temp[0]);
			$row['HomeTeam'] 	= $teams[0];
			$row['AwayTeam'] 	= $teams[1];
			$row['HomeGoal'] 	= intval($result[0]);
			$row['AwayGoal'] 	= intval($result[1]);
			$row['Sign'] 		= $temp[3];
			$rows[] = $row;
		}
		return json_encode($rows);
	}
	
	public static function getThisWeek() {
		// Create DOM from URL or file
		$html = file_get_html("http://www.svt.se/svttext/tvu/pages/552.html");
		$pre = $html->find('pre[class]', 0);
		$span = $pre->find('span');
		$str = '';
		$append = false;
		foreach($pre->find('span') as $element) {
			if(trim($element->plaintext) === '1.') {
				$append = true;
				$str .= str_replace('.','',trim($element->plaintext));
			} else if($append) {
				if(trim($element->innertext) === '') {
				} else if(preg_match("^\d((\.)+)^", $element->plaintext)) {
					$str .= '\\' . str_replace('.','',trim($element->plaintext));
				} else if (substr(trim($element->plaintext), 0, 3) == 'Oms') {
					break;
				} else if(substr($element->plaintext, 0, 1) === '-') {
					$str .= trim($element->plaintext);
				} else {
					$str .= ',' . trim($element->innertext);
				}
			}
		}
		
		$arr = explode("\\", $str);
		$rows = array();
		foreach($arr as $value) {
			$temp = explode(',', $value);
			$teams = explode('-', $temp[1]);
			$row = array();
			$row['MatchNo']		= intval($temp[0]);
			$row['HomeTeam']	= $teams[0];
			$row['AwayTeam']	= $teams[1];
			$row['HomeWin']		= intval($temp[2]);
			$row['Draw']		= intval($temp[3]);
			$row['AwayWin']		= intval($temp[4]);
			$rows[] = $row;
		}
		return json_encode($rows);
	}
}
?>
