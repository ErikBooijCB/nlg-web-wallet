<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Container\Provider;

use GuldenWallet\Backend\Application\Helper\Constant\Constant;
use GuldenWallet\Backend\Application\Helper\Constant\GlobalConstant;
use League\Container\ServiceProvider\AbstractServiceProvider;
use PDO;

class DatabaseProvider extends AbstractServiceProvider
{
    /** @var string[] */
    protected $provides = [
        PDO::class
    ];

    /**
     * @return void
     */
    public function register()
    {
        $container = $this->getContainer();

        $container->share(PDO::class, function (): PDO {
            /** @psalm-suppress UnresolvableInclude */
            $credentials = include GlobalConstant::read(Constant::CONFIGURATION_DIR) . '/credentials.php';

            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;port=%d;charset=utf8',
                $credentials['database']['host'],
                $credentials['database']['db'],
                $credentials['database']['port']
            );

            return new PDO(
                $dsn,
                $credentials['database']['user'],
                $credentials['database']['pass'],
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET time_zone = "+00:00"'
                ]
            );
        });
    }
}
