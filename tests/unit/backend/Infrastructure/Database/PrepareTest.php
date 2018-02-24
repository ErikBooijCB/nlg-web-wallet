<?php
declare(strict_types=1);

namespace GuldenWallet\Tests\Unit\Backend\Infrastructure\Database;

use GuldenWallet\Backend\Infrastructure\Database\PreparableStatement;
use GuldenWallet\Backend\Infrastructure\Database\Prepare;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

/**
 * @covers \GuldenWallet\Backend\Infrastructure\Database\Prepare
 */
class PrepareTest extends TestCase
{
    public function test_Statement_ShouldPrepareStatementAndBindValues()
    {
        $statement = self::prophesize(PreparableStatement::class);
        $pdo = self::prophesize(PDO::class);
        $pdoStatement = self::prophesize(PDOStatement::class);

        $query = 'SELECT value FROM table WHERE :key = :value';

        $statement->getStatement()->willReturn($query);
        $statement->getParameters()->willReturn([':key' => 'key', ':value' => 'value']);

        $pdo->prepare($query)->willReturn($pdoStatement);
        $pdoStatement->bindValue(':key', 'key', PDO::PARAM_STR)->will(function () use ($pdoStatement) {
            $pdoStatement->bindValue(':value', 'value', PDO::PARAM_STR)->shouldBeCalled();
        })->shouldBeCalled();

        Prepare::statement($pdo->reveal(), $statement->reveal());
    }

    /**
     * @return void
     */
    public function test_Statement_ShouldBindValueAsSpecificType_WhenProvided()
    {
        $statement = self::prophesize(PreparableStatement::class);
        $pdo = self::prophesize(PDO::class);
        $pdoStatement = self::prophesize(PDOStatement::class);

        $statement->getStatement()->willReturn('SELECT value FROM table WHERE key = :value');
        $statement->getParameters()->willReturn([':value' => [2, PDO::PARAM_INT]]);

        $pdo->prepare(Argument::type('string'))->willReturn($pdoStatement);
        $pdoStatement->bindValue(':value', 2, PDO::PARAM_INT)->shouldBeCalled();

        Prepare::statement($pdo->reveal(), $statement->reveal());
    }
}
