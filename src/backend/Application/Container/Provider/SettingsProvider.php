<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Container\Provider;

use GuldenWallet\Backend\Application\Settings\SettingsRepositoryInterface;
use GuldenWallet\Backend\Infrastructure\Settings\PdoSettingsRepository;
use League\Container\ServiceProvider\AbstractServiceProvider;
use PDO;

class SettingsProvider extends AbstractServiceProvider
{
    /** @var string[] */
    protected $provides = [
        SettingsRepositoryInterface::class,
    ];

    /**
     * @return void
     */
    public function register()
    {
        $container = $this->getContainer();

        $container->add(SettingsRepositoryInterface::class, PdoSettingsRepository::class)
            ->withArgument(PDO::class);
    }
}
