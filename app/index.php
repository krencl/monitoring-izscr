<?php

use App\Controller\ControllerInterface;
use App\Controller\ErrorController;
use App\Controller\IndexController;
use App\Controller\EventsController;
use App\DTO\Route;
use App\Service\Config;
use App\Service\Db;
use App\Service\EmailParser;
use App\Service\ImapChecker;
use App\Service\Router;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/vendor/autoload.php';

Debug::enable();

$twigLoader = new Twig\Loader\FilesystemLoader([
	__DIR__ . '/template/',
]);
$twig = new Twig\Environment($twigLoader);
$config = new Config();
$config->loadFile(__DIR__ . '/config.json');
$db = new Db($config);

$router = new Router(new Route('*', fn () => new ErrorController($twig, $config, $db)));
$router->addRoute(new Route('/', fn () => new IndexController($twig, $config, $db)));
$router->addRoute(new Route('/api/messages', fn () => new EventsController(new ImapChecker($config), new EmailParser(), $config, $db)));

$request = Request::createFromGlobals();
$route = $router->match($request);

$controllerFactory = $route->controller;
/** @var ControllerInterface $controller */
$controller = $controllerFactory();

$response = $controller->getResponse($request);
$response->send();
