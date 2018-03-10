<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Container\Provider;

use GuldenWallet\Backend\Application\Helper\Constant\Constant;
use GuldenWallet\Backend\Application\Helper\Constant\GlobalConstant;
use GuldenWallet\Backend\Application\Node\NodeClientInterface;
use GuldenWallet\Backend\Infrastructure\Node\NodeClient;
use GuldenWallet\Backend\Infrastructure\Node\NodeClientCredentials;
use GuzzleHttp\Client;
use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * @codeCoverageIgnore
 */
class NodeProvider extends AbstractServiceProvider
{
    /** @var string[] */
    protected $provides = [
        NodeClientInterface::class
    ];

    /**
     * @return void
     */
    public function register()
    {
        $container = $this->getContainer();

        $container->share(NodeClientInterface::class, NodeClient::class)
            ->withArguments([
                Client::class,
                NodeClientCredentials::class
            ]);

        $container->add(NodeClientCredentials::class, NodeClientCredentials::class)
            ->withArguments([
                '192.168.0.42',
                '9924',
                'username',
                'password'
            ]);

        $container->share(NodeClientCredentials::class, function (): NodeClientCredentials {
            /** @psalm-suppress UnresolvableInclude */
            $credentials = include GlobalConstant::read(Constant::CONFIGURATION_DIR) . '/credentials.php';

            return new NodeClientCredentials(
                $credentials['node']['host'],
                $credentials['node']['port'],
                $credentials['node']['user'],
                $credentials['node']['pass']
            );
        });
    }
}
