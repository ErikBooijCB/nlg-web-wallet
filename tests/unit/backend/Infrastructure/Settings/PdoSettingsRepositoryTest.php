<?php
declare(strict_types=1);

namespace GuldenWallet\Tests\Unit\Backend\Infrastructure\Settings;

use GuldenWallet\Backend\Application\Settings\InvalidSettingValueException;
use GuldenWallet\Backend\Application\Settings\SettingNotFoundException;
use GuldenWallet\Backend\Application\Settings\UnableToDeleteSettingException;
use GuldenWallet\Backend\Application\Settings\UnableToRetrieveSettingException;
use GuldenWallet\Backend\Application\Settings\UnableToUpdateSettingException;
use GuldenWallet\Backend\Infrastructure\Settings\PdoSettingsRepository;
use PDO;
use PDOException;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @covers \GuldenWallet\Backend\Infrastructure\Settings\PdoSettingsRepository
 */
class PdoSettingsRepositoryTest extends TestCase
{
    /** @var PDO|ObjectProphecy */
    private $connection;

    /** @var PdoSettingsRepository */
    private $settingsRepository;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->connection = self::prophesize(PDO::class);

        $this->settingsRepository = new PdoSettingsRepository($this->connection->reveal());
    }

    /**
     * @return void
     */
    public function test_Delete_ShouldDeleteSetting()
    {
        $pdoStatement = self::prophesize(PDOStatement::class);

        $this->connection->prepare(Argument::type('string'))->willReturn($pdoStatement);
        $pdoStatement->bindValue(Argument::type('string'), 'key', PDO::PARAM_STR)->shouldBeCalled();
        $pdoStatement->execute()->willReturn();

        $this->settingsRepository->delete('key');
    }

    /**
     * @return void
     */
    public function test_Delete_ShouldThrowProperException_WhenSettingCanNotBeDeleted()
    {
        self::expectException(UnableToDeleteSettingException::class);

        $pdoStatement = self::prophesize(PDOStatement::class);

        $this->connection->prepare(Argument::type('string'))->willReturn($pdoStatement);
        $pdoStatement->bindValue(Argument::cetera())->willReturn();
        $pdoStatement->execute()->willThrow(new PDOException);

        $this->settingsRepository->delete('key');
    }

    /**
     * @return void
     */
    public function test_Get_ShouldReturnSettingValue()
    {
        $pdoStatement = self::prophesize(PDOStatement::class);

        $this->connection->prepare(Argument::type('string'))->willReturn($pdoStatement);
        $pdoStatement->bindValue(Argument::type('string'), 'key', PDO::PARAM_STR)->shouldBeCalled();
        $pdoStatement->execute()->willReturn();
        $pdoStatement->fetchAll()->willReturn([
            ['SETTING_VALUE' => '{"value":"value"}']
        ]);

        self::assertEquals('value', $this->settingsRepository->get('key'));
    }

    /**
     * @return void
     */
    public function test_Get_ShouldThrowProperException_WhenSettingCanNotBeRetrieved()
    {
        self::expectException(UnableToRetrieveSettingException::class);

        $pdoStatement = self::prophesize(PDOStatement::class);

        $this->connection->prepare(Argument::type('string'))->willReturn($pdoStatement);
        $pdoStatement->bindValue(Argument::type('string'), 'key', PDO::PARAM_STR)->shouldBeCalled();
        $pdoStatement->execute()->willThrow(new PDOException);

        $this->settingsRepository->get('key');
    }

    /**
     * @return void
     */
    public function test_Get_ShouldThrowProperException_WhenSettingIsNotPresentInDataStore()
    {
        self::expectException(SettingNotFoundException::class);

        $pdoStatement = self::prophesize(PDOStatement::class);

        $this->connection->prepare(Argument::type('string'))->willReturn($pdoStatement);
        $pdoStatement->bindValue(Argument::type('string'), 'key', PDO::PARAM_STR)->shouldBeCalled();
        $pdoStatement->execute()->willReturn();
        $pdoStatement->fetchAll()->willReturn([]);

        $this->settingsRepository->get('key');
    }

    /**
     * @return void
     */
    public function test_Get_ShouldThrowProperException_WhenStoredValueCanNotBeUnserialized()
    {
        self::expectException(InvalidSettingValueException::class);

        $pdoStatement = self::prophesize(PDOStatement::class);

        $this->connection->prepare(Argument::type('string'))->willReturn($pdoStatement);
        $pdoStatement->bindValue(Argument::type('string'), 'key', PDO::PARAM_STR)->shouldBeCalled();
        $pdoStatement->execute()->willReturn();
        $pdoStatement->fetchAll()->willReturn([
            ['SETTING_VALUE' => 'some-invalid-value']
        ]);

        $this->settingsRepository->get('key');
    }

    /**
     * @return void
     */
    public function test_Get_ShouldThrowProperException_WhenStoredValueIsPersistedWrong()
    {
        self::expectException(InvalidSettingValueException::class);

        $pdoStatement = self::prophesize(PDOStatement::class);

        $this->connection->prepare(Argument::type('string'))->willReturn($pdoStatement);
        $pdoStatement->bindValue(Argument::type('string'), 'key', PDO::PARAM_STR)->shouldBeCalled();
        $pdoStatement->execute()->willReturn();
        $pdoStatement->fetchAll()->willReturn([
            ['SETTING_VALUE' => '{"wrongKey":"value}']
        ]);

        $this->settingsRepository->get('key');
    }

    /**
     * @return void
     */
    public function test_Set_ShouldPersistSetting()
    {
        $pdoStatement = self::prophesize(PDOStatement::class);

        $this->connection->prepare(Argument::type('string'))->willReturn($pdoStatement);
        $pdoStatement->bindValue(Argument::type('string'), 'key', PDO::PARAM_STR)->shouldBeCalled();
        $pdoStatement->bindValue(Argument::type('string'), '{"value":"value"}', PDO::PARAM_STR)->shouldBeCalled();
        $pdoStatement->execute()->willReturn();

        $this->settingsRepository->set('key', 'value');
    }

    /**
     * @return void
     */
    public function test_Set_ShouldThrowProperException_WhenSettingCanNotBePersisted()
    {
        self::expectException(UnableToUpdateSettingException::class);

        $pdoStatement = self::prophesize(PDOStatement::class);

        $this->connection->prepare(Argument::type('string'))->willReturn($pdoStatement);
        $pdoStatement->bindValue(Argument::cetera())->willReturn();
        $pdoStatement->execute()->willThrow(new PDOException);

        $this->settingsRepository->set('key', 'value');
    }
}
