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
		echo MatchesApi::getMatches();
		// Add exit to prevent that last characters is cut off
		// http://stackoverflow.com/questions/8811588/webkit-cutting-off-last-character-of-a-json-feed
		exit;
	})->setName('matches');

	$this->get('/{team}', function (Request $request, Response $response, $args) {
		$limit = null;
		$parm = $request->getQueryParams();
		if(array_key_exists('limit', $parm)) {
			$limit = intval($parm['limit']);
		}
		echo MatchesApi::getTeam($args['team'], $limit);
		// Add exit to prevent that last characters is cut off
		// http://stackoverflow.com/questions/8811588/webkit-cutting-off-last-character-of-a-json-feed
		exit;
	})->setName('matches-team');
});

$app->get('/stryktipset', function (Request $request, Response $response) {
	$response->getBody()->write(file_get_contents("webInterface/teststryktips.html"));
	return $response;
});

$app->get('/', function (Request $request, Response $response) {
	$response->getBody()->write("kör denna för att hämta matcher <a href='api/matches'>länk</a><br />
	Kör <a href='api/stryktipset'>denna</a> för att hämta stryktipsmatcherna<br />
	Hämta Liverpools 20 senaste matcher <a href='api/matches/Liverpool?limit=20'>här</a><br />
	Hämta matcher med Arsenal och Liverpool <a href='api/matches/Liverpool,Arsenal'>här</a><br />
	Test att hämta stryktipskupongen och lägga ut med Angular <a href='stryktipset'>här</a>");
	return $response;
});

$app->run();