<?php
declare(strict_types=1);

namespace GuldenWallet\Tests\Unit\Backend\Application\Helper\Constant;

use GuldenWallet\Backend\Application\Helper\Constant\AlreadyDefinedConstantException;
use GuldenWallet\Backend\Application\Helper\Constant\GlobalConstant;
use GuldenWallet\Backend\Application\Helper\Constant\UndefinedConstantException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \GuldenWallet\Backend\Application\Helper\Constant\GlobalConstant
 */
class GlobalConstantTest extends TestCase
{
    /**
     * @return void
     */
    public function test_HappyPath()
    {
        $constantName = 'CONSTANT_NAME';
        $constantValue = 'VALUE';

        self::assertFalse(GlobalConstant::isDefined($constantName));

        GlobalConstant::write($constantName, $constantValue);

        self::assertTrue(GlobalConstant::isDefined($constantName));
        self::assertEquals($constantValue, GlobalConstant::read($constantName));
    }

    /**
     * @return void
     */
    public function test_Read_ShouldThrowSpecificException_WhenConstantIsNotDefined()
    {
        $this->expectException(UndefinedConstantException::class);

        GlobalConstant::read('undefinedConstant');
    }

    /**
     * @return void
     */
    public function test_Write_ShouldThrowSpecificException_WhenConstantIsAlreadyDefined()
    {
        $this->expectException(AlreadyDefinedConstantException::class);

        define('SOME_CONSTANT', 'SOME_VALUE');

        GlobalConstant::write('SOME_CONSTANT', 'SOME_OTHER_VALUE');
    }

    /**
     * @return void
     */
    public function test_ReadUnsafe_ShouldNotThrowExceptionButReturnNull_WhenConstantIsNotDefined()
    {
        self::assertNull(GlobalConstant::readUnsafe('SOME_UNDEFINED_CONSTANT'));
    }
}
