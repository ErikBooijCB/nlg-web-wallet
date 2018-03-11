<?php
declare(strict_types=1);

namespace GuldenWallet\CLI;

use GuldenWallet\Backend\Application\Container\ContainerFactory;
use GuldenWallet\Backend\Application\Helper\Constant\Constant;
use GuldenWallet\Backend\Application\Helper\Constant\GlobalConstant;
use GuldenWallet\CLI\Command\ResetPasswordCommand;
use Symfony\Component\Console\Application;
use Throwable;

include __DIR__ . '/../../vendor/autoload.php';

const ROOT_PATH = __DIR__ . '/../../';

try {

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

    $application = new Application;

    $application->add($container->get(ResetPasswordCommand::class));

    $application->run();
} catch (Throwable $throwable) {
    echo 'Management application could not run. The following error was observed:', PHP_EOL, PHP_EOL;

    echo $throwable->getMessage();
}
