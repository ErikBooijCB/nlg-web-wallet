<?php
declare(strict_types=1);

use GuldenWallet\Backend\Application\Container\ContainerFactory;
use GuldenWallet\Backend\Application\Helper\Constant\Constant;
use GuldenWallet\Backend\Application\Helper\Constant\GlobalConstant;
use GuldenWallet\Backend\Application\Middleware\ExceptionHandlingMiddleware;
use GuldenWallet\Backend\Application\Middleware\NotFoundHandlingMiddleware;
use GuldenWallet\Backend\Infrastructure\Controller\AccessTokenHttpController;
use GuldenWallet\Backend\Infrastructure\Controller\NodeInformationHttpController;
use GuldenWallet\Backend\Infrastructure\Controller\SettingsHttpController;
use Slim\App;

date_default_timezone_set('UTC');

require __DIR__ . '/../../vendor/autoload.php';

const ROOT_PATH = __DIR__ . '/../../';

GlobalConstant::write(Constant::APP_ROOT, realpath(ROOT_PATH));
GlobalConstant::write(Constant::CONFIGURATION_DIR, realpath(ROOT_PATH . '/etc'));
GlobalConstant::write(Constant::LOG_DIR, realpath(ROOT_PATH . '/logs'));

$envFile = ROOT_PATH . '.ENV';

if (file_exists($envFile) && is_readable($envFile)) {
    GlobalConstant::write(Constant::ENVIRONMENT, trim(file_get_contents($envFile)));
} else {
    GlobalConstant::write(Constant::ENVIRONMENT, 'production');
}

$container = ContainerFactory::create();

/** @var App $app */
$app = $container->get(App::class);
$app->add(ExceptionHandlingMiddleware::class);
$app->add(NotFoundHandlingMiddleware::class);

/*******************************************/
/* ACCESS TOKENS                            /
/*******************************************/
$app->delete('/access-tokens/{token:[a-f0-9]+}', AccessTokenHttpController::class . ':revoke');
$app->get('/access-tokens/{token:[a-f0-9]+}', AccessTokenHttpController::class . ':retrieve');
$app->post('/access-tokens', AccessTokenHttpController::class . ':create');
$app->post('/access-tokens/{token:[a-f0-9]+}', AccessTokenHttpController::class . ':refresh');

/*******************************************/
/* NODE                                     /
/*******************************************/
$app->get('/node', NodeInformationHttpController::class . ':overview');

/*******************************************/
/* SETTINGS                                 /
/*******************************************/
$app->get('/settings/{setting:[A-z\-]+}', SettingsHttpController::class . ':get');
$app->put('/settings/{setting:[A-z\-]+}', SettingsHttpController::class . ':set');
$app->delete('/settings/{setting:[A-z\-]+}', SettingsHttpController::class . ':delete');

$app->run();
