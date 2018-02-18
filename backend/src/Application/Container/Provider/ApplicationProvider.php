<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Container\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Slim\App;
use Slim\CallableResolver;

/**
 * @codeCoverageIgnore
 */
class ApplicationProvider extends AbstractServiceProvider
{
    /** @var string[] */
    protected $provides = [
        App::class,
        'callableResolver',
    ];

    /**
     * @return void
     */
    public function register()
    {
        $container = $this->getContainer();

        $container->add(App::class, App::class)
            ->withArgument($container);

        $container->add('callableResolver', CallableResolver::class)
            ->withArgument($container);
    }
}
