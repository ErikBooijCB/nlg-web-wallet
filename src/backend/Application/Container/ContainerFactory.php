<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Container;

use GuldenWallet\Backend\Application\Container\Provider\AccessTokenProvider;
use GuldenWallet\Backend\Application\Container\Provider\ApplicationProvider;
use GuldenWallet\Backend\Application\Container\Provider\DatabaseProvider;
use GuldenWallet\Backend\Application\Container\Provider\NodeProvider;
use GuldenWallet\Backend\Application\Helper\Constant\Constant;
use GuldenWallet\Backend\Application\Helper\Constant\GlobalConstant;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Psr\Container\ContainerInterface;
use Slim\Container as SlimContainer;

/**
 * @codeCoverageIgnore
 */
class ContainerFactory
{
    /**
     * @return ContainerInterface
     */
    public static function create(): ContainerInterface
    {
        $container = new Container;

        $container->delegate(new ReflectionContainer);
        $container->delegate(self::createSlimContainer());

        $container->addServiceProvider(AccessTokenProvider::class);
        $container->addServiceProvider(ApplicationProvider::class);
        $container->addServiceProvider(DatabaseProvider::class);
        $container->addServiceProvider(NodeProvider::class);

        return $container;
    }

    /**
     * @return SlimContainer
     */
    private static function createSlimContainer(): SlimContainer
    {
        $slimContainer = new SlimContainer(self::getSlimConfig());

        unset($slimContainer['errorHandler']);
        unset($slimContainer['phpErrorHandler']);

        return $slimContainer;
    }

    /**
     * @return array
     */
    private static function getSlimConfig(): array
    {
        $config = [
            'settings' => [
                'debug' => true,
                'addContentLengthHeader' => true,
                'determineRouteBeforeAppMiddleware' => true,
                'displayErrorDetails' => true,
                'httpVersion' => '1.1',
                'logger' => [
                    'name' => 'gulden-wallet',
                ],
                'outputBuffering' => 'append',
                'responseChunkSize' => 4096,
                'routerCacheFile' => false,
            ],
        ];

        if (GlobalConstant::isDefined(Constant::LOG_DIR)) {
            $config['settings']['logger']['path'] = GlobalConstant::readUnsafe(Constant::LOG_DIR);
        }

        return $config;
    }
}
