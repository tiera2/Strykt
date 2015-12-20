<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

$app = new \Slim\App;

$app->get('/api/stryktipset[/]', function (Request $request, Response $response) {
	$response = $response->withHeader('Content-type', 'application/json');
	require 'api/cStryktipsetApi.php';
	$response->getBody()->write(StryktipsetApi::getRow());
	return $response;
});

$app->get('/api/matches[/]', function (Request $request, Response $response) {
	$response = $response->withHeader('Content-type', 'application/json');
	require 'api/cMatchesApi.php';
	$response->getBody()->write(MatchesApi::getMatches());
	return $response;
});

$app->get('/api/matches/{team}', function (Request $request, Response $response, $args) {
	$response = $response->withHeader('Content-type', 'application/json');
	require 'api/cMatchesApi.php';
	$response->getBody()->write(MatchesApi::getTeam($args['team']));
	return $response;
});

$app->get('/', function (Request $request, Response $response) {
	$response->getBody()->write("kör denna för att hämta matcher <a href='api/matches'>länk</a><br />
	Kör <a href='api/stryktipset'>denna</a> för att hämta stryktipsmatcherna<br />
	Hämta Liverpools matcher <a href='api/matches/Liverpool'>här</a>");
	return $response;
});

$app->run();

