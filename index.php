<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

$app = new \Slim\App;

$app->get('/api/matches', function (Request $request, Response $response) {
	$response = $response->withHeader('Content-type', 'application/json');
	require 'api/cMatchesApi.php';
	$response->getBody()->write(MatchesApi::getCsv());
	return $response;
});

$app->get('/', function (Request $request, Response $response) {
	/** Egentligen ska man inte behöva ange index.php här men man behöver url rewriting först tror jag **/
	$response->getBody()->write("kör denna för att hämta matcher <a href='index.php/api/matches'>länk</a>");
	return $response;
});
$app->run();

