<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\NotFoundException;
use Slim\Factory\AppFactory;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Tourism\InfoMapper;

require __DIR__ . '/vendor/autoload.php';

$loader = new FilesystemLoader('templates');
$view = new Environment($loader);

$config = include 'config/database.php';
$dsn = $config['dsn'];
$username = $config['username'];
$password = $config['password'];

try{
	$connection = new PDO($dsn, $username, $password);
	$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$connection->query("SET NAMES utf8");
} catch (PDOException $exception) {
	echo 'Database error: ' . $exception->getMessage();
	exit;
}

$infoMapper = new InfoMapper($connection);

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) use ($view){
    $body = $view->render('mainMenu.html');
    $response->getBody()->write($body);
    return $response;
});

$app->get('/tours.html', function (Request $request, Response $response, $args) use ($view){
    $body = $view->render('tours.html');
    $response->getBody()->write($body);
    return $response;
});

$app->get('/gruziya.html', function (Request $request, Response $response, $args) use ($view, $infoMapper){
    $info = $infoMapper->getByUrlKey('gruziya');
	$body = $view->render('gruziya.html', ['info' => $info,'comment' => read_last_comment ('comments.txt')]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/sin.html', function (Request $request, Response $response, $args) use ($view){
    $body = $view->render('sin.html');
    $response->getBody()->write($body);
    return $response;
});

$app->get('/register.html', function (Request $request, Response $response, $args) use ($view){
    $body = $view->render('register.html');
    $response->getBody()->write($body);
    return $response;
});

function read_last_comment ($file_path){

	$line = '';
	$f = fopen($file_path, 'r');
	$cursor = -1;
	
	fseek($f, $cursor, SEEK_END);
	$char = fgetc($f);
	
	while ($char === "\n" || $char === "\r") {
		fseek($f, $cursor--, SEEK_END);
		$char = fgetc($f);
	}

	while ($char !== false && $char !== "\n" && $char !== "\r") {
		$line = $char . $line;
		fseek($f, $cursor--, SEEK_END);
		$char = fgetc($f);
	}
	return $line;
	}

$app->run();