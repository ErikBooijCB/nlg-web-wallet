<?php
declare(strict_types=1);

namespace GuldenWallet\Tests\Unit\Backend\Infrastructure\Controller;

use GuldenWallet\Backend\Application\Settings\InvalidSettingValueException;
use GuldenWallet\Backend\Application\Settings\SettingNotFoundException;
use GuldenWallet\Backend\Application\Settings\SettingsRepositoryInterface;
use GuldenWallet\Backend\Application\Settings\UnableToRetrieveSettingException;
use GuldenWallet\Backend\Application\Settings\UnableToUpdateSettingException;
use GuldenWallet\Backend\Infrastructure\Controller\SettingsHttpController;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @covers \GuldenWallet\Backend\Infrastructure\Controller\SettingsHttpController
 */
class SettingsHttpControllerTest extends TestCase
{
    /** @var SettingsHttpController */
    private $controller;

    /** @var ServerRequestInterface|ObjectProphecy */
    private $request;

    /** @var SettingsRepositoryInterface|ObjectProphecy */
    private $settingsRepository;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->request = self::prophesize(ServerRequestInterface::class);
        $this->settingsRepository = self::prophesize(SettingsRepositoryInterface::class);

        $this->controller = new SettingsHttpController($this->settingsRepository->reveal());
    }

    /**
     * @return void
     */
    public function test_Delete_ShouldReturn202_WhenSettingsIsSuccessfullyDelete()
    {
        $this->request->getAttribute('setting', '')->willReturn('key');
        $this->settingsRepository->delete('key')->shouldBeCalled();

        $response = $this->controller->delete($this->request->reveal());

        self::assertEquals(202, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_Delete_ShouldReturn400_WhenNoSettingKeyIsProvided()
    {
        $this->request->getAttribute('setting', '')->willReturn('');

        $response = $this->controller->delete($this->request->reveal());

        self::assertEquals(400, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_Delete_ShouldReturn500_WhenSettingsCanNotBeDeleted()
    {
        $this->request->getAttribute('setting', '')->willReturn('key');
        $this->settingsRepository->delete('key')->willThrow(new UnableToUpdateSettingException);

        $response = $this->controller->delete($this->request->reveal());

        self::assertEquals(500, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_Get_ShouldReturn200AndSettingValue_WhenSettingCanBeRetrieved()
    {
        $this->request->getAttribute('setting', '')->willReturn('key');
        $this->settingsRepository->get('key')->willReturn('value');

        $response = $this->controller->get($this->request->reveal());

        $responseBody = json_decode($response->getBody()->getContents(), true);

        self::assertEquals(200, $response->getStatusCode());
        self::assertArrayHasKey('key', $responseBody['data']);
        self::assertArrayHasKey('value', $responseBody['data']);
        self::assertEquals('key', $responseBody['data']['key']);
        self::assertEquals('value', $responseBody['data']['value']);
    }

    /**
     * @return void
     */
    public function test_Get_ShouldReturn400_WhenNoSettingKeyIsProvided()
    {
        $this->request->getAttribute('setting', '')->willReturn('');

        $response = $this->controller->get($this->request->reveal());

        self::assertEquals(400, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_Get_ShouldReturn404_WhenSettingDoesNotExist()
    {
        $this->request->getAttribute('setting', '')->willReturn('key');
        $this->settingsRepository->get('key')->willThrow(new SettingNotFoundException);

        $response = $this->controller->get($this->request->reveal());

        self::assertEquals(404, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_Get_ShouldReturn500_WhenSettingCanNotBeRetrieved()
    {
        $this->request->getAttribute('setting', '')->willReturn('key');
        $this->settingsRepository->get('key')->willThrow(new UnableToRetrieveSettingException);

        $response = $this->controller->get($this->request->reveal());

        self::assertEquals(500, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_Get_ShouldReturn500_WhenStoredSettingValueIsUnusable()
    {
        $this->request->getAttribute('setting', '')->willReturn('key');
        $this->settingsRepository->get('key')->willThrow(new InvalidSettingValueException);

        $response = $this->controller->get($this->request->reveal());

        self::assertEquals(500, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_Set_ShouldReturn201_WhenSettingCanBePersisted()
    {
        $this->request->getAttribute('setting', '')->willReturn('key');
        $this->request->getParsedBody()->willReturn(['value' => true]);

        $this->settingsRepository->set('key', true)->willReturn();

        $response = $this->controller->set($this->request->reveal());

        self::assertEquals(201, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_Set_ShouldReturn400_WhenNoSettingKeyIsProvided()
    {
        $this->request->getAttribute('setting', '')->willReturn('');

        $response = $this->controller->set($this->request->reveal());

        self::assertEquals(400, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_Set_ShouldReturn400_WhenNoSettingValueIsProvided()
    {
        $this->request->getAttribute('setting', '')->willReturn('key');
        $this->request->getParsedBody()->willReturn([]);

        $response = $this->controller->set($this->request->reveal());

        self::assertEquals(400, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_Set_ShouldReturn500_WhenSettingCanNotBePersisted()
    {
        $this->request->getAttribute('setting', '')->willReturn('key');
        $this->request->getParsedBody()->willReturn(['value' => true]);

        $this->settingsRepository->set('key', true)->willThrow(new UnableToUpdateSettingException);

        $response = $this->controller->set($this->request->reveal());

        self::assertEquals(500, $response->getStatusCode());
    }
}
