<?php
declare(strict_types=1);

use GuldenWallet\Backend\Application\Container\ContainerFactory;
use GuldenWallet\Backend\Application\Helper\Constant\Constant;
use GuldenWallet\Backend\Application\Helper\Constant\GlobalConstant;
use GuldenWallet\Backend\Application\Middleware\ExceptionHandlingMiddleware;
use GuldenWallet\Backend\Application\Middleware\NotFoundHandlingMiddleware;
use GuldenWallet\Backend\Infrastructure\Controller\AccessTokenHttpController;
use Slim\App;

require __DIR__ . '/../vendor/autoload.php';

GlobalConstant::write(Constant::APP_ROOT, realpath(__DIR__ . '/../'));
GlobalConstant::write(Constant::CONFIGURATION_DIR, realpath(__DIR__ . '/../etc'));
GlobalConstant::write(Constant::ENVIRONMENT, trim(file_get_contents(__DIR__ . '/../.ENV') ?: 'production'));
GlobalConstant::write(Constant::LOG_DIR, realpath(__DIR__ . '/../logs'));

$container = ContainerFactory::create();

/** @var App $app */
$app = $container->get(App::class);
$app->add(ExceptionHandlingMiddleware::class);
$app->add(NotFoundHandlingMiddleware::class);

$app->group('/api', function () {
    $this->delete('/access-tokens/{token:[a-f0-9]+}', AccessTokenHttpController::class . ':delete');
    $this->post('/access-tokens', AccessTokenHttpController::class . ':post');
});

$app->run();
