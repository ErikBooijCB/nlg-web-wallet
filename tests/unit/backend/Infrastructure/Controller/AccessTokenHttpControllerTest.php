<?php
declare(strict_types=1);

namespace GuldenWallet\Tests\Unit\Backend\Infrastructure\Controller;

use DateTimeImmutable;
use GuldenWallet\Backend\Application\Access\AccessToken;
use GuldenWallet\Backend\Application\Access\AccessTokenNotFoundException;
use GuldenWallet\Backend\Application\Access\AccessTokenServiceInterface;
use GuldenWallet\Backend\Application\Access\TokenIdentifier;
use GuldenWallet\Backend\Application\Access\UnableToCreateAccessTokenException;
use GuldenWallet\Backend\Application\Access\UnableToExpireAccessTokenException;
use GuldenWallet\Backend\Application\Access\UnableToRetrieveAccessTokenException;
use GuldenWallet\Backend\Domain\Access\InvalidCredentialsException;
use GuldenWallet\Backend\Infrastructure\Controller\AccessTokenHttpController;
use GuldenWallet\Tests\Fixtures\Access\AccessTokenFixture;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TheSeer\Tokenizer\TokenCollectionException;

/**
 * @covers \GuldenWallet\Backend\Infrastructure\Controller\AccessTokenHttpController
 */
class AccessTokenHttpControllerTest extends TestCase
{
    /** @var AccessTokenServiceInterface|ObjectProphecy */
    private $accessTokenService;

    /** @var AccessTokenHttpController */
    private $controller;

    /** @var ServerRequestInterface|ObjectProphecy */
    private $request;

    /**
     * @return array
     */
    public function incompletePostRequestDataProvider(): array
    {
        return [
            [['email' => 'john@doe.com']],
            [['password' => 'some-password']],
        ];
    }

    /**
     * @return void
     */
    public function setUp()
    {
        $this->accessTokenService = self::prophesize(AccessTokenServiceInterface::class);
        $this->request = self::prophesize(ServerRequestInterface::class);

        $this->controller = new AccessTokenHttpController($this->accessTokenService->reveal());
    }

    /**
     * @return void
     */
    public function test_Delete_ShouldReturn204Status_WhenAccessTokenIsDeleted()
    {
        $token = '0000000000000000000000000000000000000000000000000000000000000000';

        $this->request->getAttribute('token', '')->willReturn($token);

        $this->accessTokenService->expireToken(TokenIdentifier::fromString($token))
            ->shouldBeCalled();

        $response = $this->controller->delete($this->request->reveal());

        self::assertEquals(204, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_Delete_ShouldReturn400Error_WhenIncorrectTokenIsProvided()
    {
        $this->request->getAttribute('token', '')->willReturn('test');

        $response = $this->controller->delete($this->request->reveal());

        self::assertEquals(400, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_Delete_ShouldReturn500Error_WhenAccessTokenCannotBeRevoked()
    {
        $token = '0000000000000000000000000000000000000000000000000000000000000000';

        $this->request->getAttribute('token', '')->willReturn($token);

        $this->accessTokenService->expireToken(TokenIdentifier::fromString($token))
            ->willThrow(new UnableToExpireAccessTokenException);

        $response = $this->controller->delete($this->request->reveal());

        self::assertEquals(500, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_PostShouldReturn401Error_WhenIncorrectCredentialsAreProvided()
    {
        $this->request->getParsedBody()->willReturn([
            'email'    => 'john@doe.com',
            'password' => 'some-password',
        ]);

        $this->accessTokenService->createToken(Argument::cetera())->willThrow(new InvalidCredentialsException);

        $response = $this->controller->post($this->request->reveal());

        self::assertEquals(401, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_PostShouldReturn500Error_WhenTokenCannotBeCreated()
    {
        $this->request->getParsedBody()->willReturn([
            'email'    => 'john@doe.com',
            'password' => 'some-password',
        ]);

        $this->accessTokenService->createToken(Argument::cetera())->willThrow(new UnableToCreateAccessTokenException);

        $response = $this->controller->post($this->request->reveal());

        self::assertEquals(500, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_Post_ShouldCreateNewAccessToken_WhenCredentialsAreValid()
    {
        $this->request->getParsedBody()->willReturn([
            'email'    => 'test@test.com',
            'password' => 'some-password',
        ]);

        $accessToken = new AccessToken(
            TokenIdentifier::generate(),
            $expiration = new DateTimeImmutable,
            TokenIdentifier::generate()
        );

        $this->accessTokenService->createToken('test@test.com', 'some-password', Argument::type(\DateInterval::class))
            ->willReturn($accessToken);

        $response = $this->controller->post($this->request->reveal());

        $responseData = json_decode($response->getBody()->getContents(), true);

        self::assertEquals('ok', $responseData['status']);
        self::assertArrayHasKey('accessToken', $responseData['data']);
        self::assertArrayHasKey('expires', $responseData['data']);
        self::assertArrayHasKey('refreshToken', $responseData['data']);
    }

    /**
     * @param array $requestBody
     *
     * @return void
     *
     * @dataProvider incompletePostRequestDataProvider
     */
    public function test_Post_ShouldReturn400Error_WhenEmailOrPasswordIsMissingFromRequest(array $requestBody)
    {
        $this->request->getParsedBody()->willReturn($requestBody);

        $response = $this->controller->post($this->request->reveal());

        self::assertEquals(400, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_Get_ShouldReturnAccessToken_WhenItExists()
    {
        $accessToken = AccessTokenFixture::accessToken();

        $this->request->getAttribute('token', '')->willReturn(AccessTokenFixture::tokenIdentifier()->toString());
        $this->accessTokenService->getAccessTokenByIdentifier(AccessTokenFixture::tokenIdentifier())
            ->willReturn($accessToken);

        $response = $this->controller->get($this->request->reveal());
        $responseData = json_decode($response->getBody()->getContents(), true);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('ok', $responseData['status']);
        self::assertEquals('2018-10-12T12:34:56+00:00', $responseData['data']['expires']);
    }

    /**
     * @return void
     */
    public function test_Get_ShouldReturn404_WhenTokenDoesNotExist()
    {
        $this->request->getAttribute('token', '')->willReturn(AccessTokenFixture::tokenIdentifier()->toString());
        $this->accessTokenService->getAccessTokenByIdentifier(AccessTokenFixture::tokenIdentifier())
            ->willThrow(new AccessTokenNotFoundException);

        $response = $this->controller->get($this->request->reveal());
        $responseData = json_decode($response->getBody()->getContents(), true);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals(404, $response->getStatusCode());
        self::assertEquals('error', $responseData['status']);
    }

    /**
     * @return void
     */
    public function test_Get_ShouldReturn500_WhenTokenDoesNotExist()
    {
        $this->request->getAttribute('token', '')->willReturn(AccessTokenFixture::tokenIdentifier()->toString());
        $this->accessTokenService->getAccessTokenByIdentifier(AccessTokenFixture::tokenIdentifier())
            ->willThrow(new UnableToRetrieveAccessTokenException());

        $response = $this->controller->get($this->request->reveal());
        $responseData = json_decode($response->getBody()->getContents(), true);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals(500, $response->getStatusCode());
        self::assertEquals('error', $responseData['status']);
    }

    /**
     * @return void
     */
    public function test_Get_ShouldReturn400_WhenTokenWithInvalidFormatIsPassed()
    {
        $this->request->getAttribute('token', '')->willReturn('some-wrong-identifier');

        $response = $this->controller->get($this->request->reveal());
        $responseData = json_decode($response->getBody()->getContents(), true);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals(400, $response->getStatusCode());
        self::assertEquals('error', $responseData['status']);
    }
}
