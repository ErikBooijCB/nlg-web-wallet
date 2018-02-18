<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Container\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Slim\App;
use Slim\CallableResolver;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class ApplicationProvider extends AbstractServiceProvider
{
    /** @var string[] */
    protected $provides = [
        App::class,
        Run::class,
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

        $container->share(Run::class, function (): Run {
            return (new Run)->pushHandler(new PrettyPageHandler);
        });
    }
}
