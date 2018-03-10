<?php
declare(strict_types=1);

namespace GuldenWallet\Tests\Support\Helpers\Node;

use GuldenWallet\Backend\Infrastructure\Node\NodeClientCredentials;

class NodeClientCredentialsFixture
{
    /**
     * @param string $host
     * @param int $port
     * @param string $user
     * @param string $pass
     * @return NodeClientCredentials
     */
    public static function standard(
        string $host = 'localhost',
        int $port = 9232,
        string $user = 'user',
        string $pass = 'pass'
    ): NodeClientCredentials {
        return new NodeClientCredentials($host, $port, $user, $pass);
    }
}
