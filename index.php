<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

$app = new \Slim\App;

$app->add(function (Request $request, Response $response, callable $next) {
    $uri = $request->getUri();
    $path = $uri->getPath();
    if ($path != '/' && substr($path, -1) == '/') {
        // permanently redirect paths with a trailing slash
        // to their non-trailing counterpart
        $uri = $uri->withPath(substr($path, 0, -1));
        return $response->withRedirect((string)$uri, 301);
    }

    return $next($request, $response);
});


$app->get('/api/stryktipset', function (Request $request, Response $response) {
	$response = $response->withHeader('Content-type', 'application/json');
	require 'api/cStryktipsetApi.php';
	$response->getBody()->write(StryktipsetApi::getThisWeek());
	return $response;
});

$app->get('/api/stryktipset/last', function (Request $request, Response $response) {
	$response = $response->withHeader('Content-type', 'application/json');
	require 'api/cStryktipsetApi.php';
	$response->getBody()->write(StryktipsetApi::getRow());
	return $response;
});

$app->get('/api/matches', function (Request $request, Response $response) {
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
	Hämta Liverpools matcher <a href='api/matches/Liverpool'>här</a><br />
	Hämta matcher med Arsenal och Liverpool <a href='api/matches/Liverpool,Arsenal'>här</a>");
	return $response;
});

$app->run();