<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'app/vendor/autoload.php';

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

$app->group('/api/stryktipset', function () {
	require 'app/api/cStryktipsetApi.php';
	
	$this->get('', function (Request $request, Response $response) {
		$response = $response->withHeader('Content-type', 'application/json');
		$response->getBody()->write(StryktipsetApi::getThisWeek());
		return $response;
	})->setName('stryktipset');

	$this->get('/last', function (Request $request, Response $response) {
		$response = $response->withHeader('Content-type', 'application/json');
		$response->getBody()->write(StryktipsetApi::getRow());
		return $response;
	})->setName('stryktipset-last');
});

$app->group('/api/matches', function () {
	require 'app/api/cMatchesApi.php';
	header("Content-Type: application/json");

	$this->get('', function (Request $request, Response $response) {
		flush();
		return MatchesApi::getMatches();
	})->setName('matches');

	$this->get('/{team}', function (Request $request, Response $response, $args) {
		$limit = null;
		$parm = $request->getQueryParams();
		if(array_key_exists('limit', $parm)) {
			$limit = intval($parm['limit']);
		}
		flush();
		return MatchesApi::getTeam($args['team'], $limit);
	})->setName('matches-team');
});

$app->get('/api/tables', function (Request $request, Response $response, $args) {
	$response = $response->withHeader('Content-type', 'application/json');
	require 'app/api/cTablesApi.php';
	$table = TablesApi::getPremierLeagueTable();
	$response->getBody()->write($table);
	return $response;
});

$app->get('/stryktipset', function (Request $request, Response $response) {
	$response->getBody()->write(file_get_contents("webInterface/views/stryktipsView.html"));
	return $response;
});

$app->get('/matches', function (Request $request, Response $response) {
	$response->getBody()->write(file_get_contents("webInterface/views/matchesView.html"));
	return $response;
});

$app->get('/table', function (Request $request, Response $response) {
	$response->getBody()->write(file_get_contents("webInterface/views/tableView.html"));
	return $response;
});

$app->get('/', function (Request $request, Response $response) {
	$response->getBody()->write(file_get_contents("webInterface/index.html"));
	return $response;
});

$app->run();