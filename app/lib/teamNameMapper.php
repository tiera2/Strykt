<?php

function getTeamName($name) {
	$name = $name === 'Arsenal FC' ? 'Arsenal' : $name;
	$name = $name === 'Aston V.' ? 'Aston Villa' : $name;
	$name = $name === 'Aston Villa FC' ? 'Aston Villa' : $name;
	$name = $name === 'Birmingh.' ? 'Birmingham' : $name;
	$name = $name === 'Bournem.' ? 'Bournemouth' : $name;
	$name = $name === 'Crystal P' ? 'Crystal Palace' : $name;
	$name = $name === 'Leicester City FC' ? 'Leicester' : $name;
	$name = $name === 'Liverpool FC' ? 'Liverpool' : $name;
	$name = $name === 'Manch.C' ? 'Man City' : $name;
	$name = $name === 'Manchester City FC' ? 'Man City' : $name;
	$name = $name === 'Middlesbr' ? 'Middlesbrough' : $name;
	$name = $name === 'Milton Ke' ? 'Milton Keynes Dons' : $name;
	$name = $name === 'Queens PR' ? 'QPR' : $name;
	$name = $name === 'Sheff.W' ? 'Sheffield Weds' : $name;
	$name = $name === 'Sunderl.' ? 'Sunderland' : $name;
	$name = $name === 'Sunderland AFC' ? 'Sunderland' : $name;
	$name = $name === 'Tottenham Hotspur FC' ? 'Tottenham' : $name;
	$name = $name === 'West Bromwich Albion FC' ? 'West Brom' : $name;
	return $name;
}