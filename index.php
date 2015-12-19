<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

$app = new \Slim\App;
$app->get('/api/matches/', function (Request $request, Response $response) {
	$response = $response->withHeader('Content-type', 'application/json');
	require 'api/cMatchesApi.php';
	$response->getBody()->write(MatchesApi::getCsv());
	return $response;
});

$app->get('/', function (Request $request, Response $response) {
	$response->getBody()->write("kör denna för att hämta matcher <a href='./api/matches/'>länk</a>");
	return $response;
});
$app->run();

