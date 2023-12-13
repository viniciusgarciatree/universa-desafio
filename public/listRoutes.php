<?php
// Inclua o autoload do Composer para carregar as classes do Zend Framework
use Zend\Mvc\Application;
use Zend\Router\Http\TreeRouteStack;

chdir(dirname(__DIR__));

include __DIR__ . '/../vendor/autoload.php';

$appConfig = require __DIR__ . '/../config/application.config.php';

// Inicialize o aplicativo MVC do Zend Framework
$application = Application::init($appConfig);

// Obtenha o ServiceManager
$serviceManager = $application->getServiceManager();

// Obtenha o Router da aplicação
/** @var TreeRouteStack $router */
$router = $serviceManager->get('Router');

// Obtenha as rotas registradas
$routes = $router->getRoutes();

// Exiba informações sobre as rotas
echo "Listagem de Rotas Registradas:\n";
echo "==============================\n";

foreach ($routes as $routeName => $route) {
    echo "Nome da Rota: $routeName\n";
    echo "    Tipo: " . get_class($route) . "\n";
    echo "    Especificação: " . $route->assemble() . "\n";
    echo "----------------------------------\n";
}