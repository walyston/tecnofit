<?php

declare(strict_types=1);

use config\Http\Route;
use config\Http\Router;
use config\Database\Connection;
use Movement\Ranking\Infrastructure\PdoRankingRepository;
use Movement\Ranking\Application\GetMovementRanking;
use Movement\Ranking\Controller\MovementRankingController;

require_once __DIR__ . '/../config/Database/Connection.php';
require_once __DIR__ . '/../config/Http/Route.php';
require_once __DIR__ . '/../config/Http/Router.php';

require_once __DIR__ . '/../src/Movement/Ranking/Domain/RankingEntry.php';
require_once __DIR__ . '/../src/Movement/Ranking/Domain/RankingRepository.php';
require_once __DIR__ . '/../src/Movement/Ranking/Domain/MovementRankingResult.php';
require_once __DIR__ . '/../src/Movement/Ranking/Infrastructure/PdoRankingRepository.php';
require_once __DIR__ . '/../src/Movement/Ranking/Application/GetMovementRanking.php';
require_once __DIR__ . '/../src/Movement/Ranking/Controller/MovementRankingController.php';

header('Content-Type: application/json');

set_exception_handler(function (Throwable $e) {
    http_response_code(500);

    echo json_encode([
        'error' => $e->getMessage()
    ]);
    exit;
});

try {

    /*
    |--------------------------------------------------------------------------
    | Bootstrap Core Dependencies
    |--------------------------------------------------------------------------
    */

    $pdo = Connection::getInstance();
    $rankingRepository = new PdoRankingRepository($pdo);
    $getMovementRanking = new GetMovementRanking($rankingRepository);

    /*
    |--------------------------------------------------------------------------
    | Router Configuration
    |--------------------------------------------------------------------------
    */

    $router = new Router();

    $router->add(new Route(
        method: 'GET',
        path: '/movements/{movementId}/ranking',
        controller: MovementRankingController::class,
        action: 'handle'
    ));

    /*
    |--------------------------------------------------------------------------
    | Request Dispatch
    |--------------------------------------------------------------------------
    */

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $method = $_SERVER['REQUEST_METHOD'];

    $routeData = $router->dispatch($method, $uri);

    $controllerClass = $routeData['controller'];
    $action = $routeData['action'];
    $params = $routeData['params'];

    /*
    |--------------------------------------------------------------------------
    | Dependency Injection Manual
    |--------------------------------------------------------------------------
    */

    switch ($controllerClass) {

        case MovementRankingController::class:
            $controller = new MovementRankingController($getMovementRanking);
            break;

        default:
            throw new Exception('Controller not configured');
    }

    /*
    |--------------------------------------------------------------------------
    | Controller Execution
    |--------------------------------------------------------------------------
    */

    $response = call_user_func_array(
        [$controller, $action],
        $params
    );

    echo json_encode($response);

} catch (Throwable $e) {

    http_response_code(500);

    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
