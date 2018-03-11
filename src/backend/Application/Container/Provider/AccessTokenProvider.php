<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Container\Provider;

use GuldenWallet\Backend\Application\Access\AccessTokenServiceInterface;
use GuldenWallet\Backend\Infrastructure\Access\PdoAccessTokenService;
use League\Container\ServiceProvider\AbstractServiceProvider;
use PDO;

/**
 * @codeCoverageIgnore
 */
class AccessTokenProvider extends AbstractServiceProvider
{
    /** @var string[] */
    protected $provides = [
        AccessTokenServiceInterface::class
    ];

    /**
     * @return void
     */
    public function register()
    {
        $container = $this->getContainer();

        $container->add(AccessTokenServiceInterface::class, PdoAccessTokenService::class)
            ->withArgument(PDO::class);
    }
}
