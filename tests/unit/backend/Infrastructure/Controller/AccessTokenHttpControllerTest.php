<?php
declare(strict_types=1);

namespace GuldenWallet\Tests\Unit\Backend\Infrastructure\Controller;

use DateTimeImmutable;
use GuldenWallet\Backend\Application\Access\AccessToken;
use GuldenWallet\Backend\Application\Access\AccessTokenNotFoundException;
use GuldenWallet\Backend\Application\Access\AccessTokenServiceInterface;
use GuldenWallet\Backend\Application\Access\InvalidRefreshTokenException;
use GuldenWallet\Backend\Application\Access\TokenIdentifier;
use GuldenWallet\Backend\Application\Access\UnableToCreateAccessTokenException;
use GuldenWallet\Backend\Application\Access\UnableToExpireAccessTokenException;
use GuldenWallet\Backend\Application\Access\UnableToRefreshTokenException;
use GuldenWallet\Backend\Application\Access\UnableToRetrieveAccessTokenException;
use GuldenWallet\Backend\Domain\Access\InvalidCredentialsException;
use GuldenWallet\Backend\Infrastructure\Controller\AccessTokenHttpController;
use GuldenWallet\Tests\Support\Fixtures\Access\AccessTokenFixture;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

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
     * @return array[]
     */
    public function _refreshTokenExceptionDataProvider(): array
    {
        return [
            [new AccessTokenNotFoundException, 404],
            [new InvalidRefreshTokenException, 401],
            [new UnableToRefreshTokenException, 500],
        ];
    }

    /**
     * @return array
     */
    public function _incompletePostRequestDataProvider(): array
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
    public function test_Create_ShouldCreateNewAccessToken_WhenCredentialsAreValid()
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

        $response = $this->controller->create($this->request->reveal());

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
     * @dataProvider _incompletePostRequestDataProvider
     */
    public function test_Create_ShouldReturn400Error_WhenEmailOrPasswordIsMissingFromRequest(array $requestBody)
    {
        $this->request->getParsedBody()->willReturn($requestBody);

        $response = $this->controller->create($this->request->reveal());

        self::assertEquals(400, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_Create_ShouldReturn401Error_WhenIncorrectCredentialsAreProvided()
    {
        $this->request->getParsedBody()->willReturn([
            'email'    => 'john@doe.com',
            'password' => 'some-password',
        ]);

        $this->accessTokenService->createToken(Argument::cetera())->willThrow(new InvalidCredentialsException);

        $response = $this->controller->create($this->request->reveal());

        self::assertEquals(401, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_Create_ShouldReturn500Error_WhenTokenCannotBeCreated()
    {
        $this->request->getParsedBody()->willReturn([
            'email'    => 'john@doe.com',
            'password' => 'some-password',
        ]);

        $this->accessTokenService->createToken(Argument::cetera())->willThrow(new UnableToCreateAccessTokenException);

        $response = $this->controller->create($this->request->reveal());

        self::assertEquals(500, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_Refresh_ShouldReturn400StatusCode_WhenProvidedAccessTokenIsInvalid()
    {
        $this->request->getAttribute('token', '')->willReturn('wrong-token');

        $response = $this->controller->refresh($this->request->reveal());

        self::assertEquals(400, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_Refresh_ShouldReturn400StatusCode_WhenProvidedRefreshTokenIsInvalid()
    {
        $accessToken = AccessTokenFixture::randomTokenIdentifier()->toString();

        $this->request->getAttribute('token', '')->willReturn($accessToken);
        $this->request->getParsedBody()->willReturn([
            'refresh' => 'wrong-token',
        ]);

        $response = $this->controller->refresh($this->request->reveal());

        self::assertEquals(400, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_Refresh_ShouldReturnNewToken_WhenRefreshIsSuccessful()
    {
        $accessToken = AccessTokenFixture::randomTokenIdentifier()->toString();
        $refreshToken = AccessTokenFixture::randomTokenIdentifier()->toString();

        $this->request->getAttribute('token', '')->willReturn($accessToken);
        $this->request->getParsedBody()->willReturn([
            'refresh' => $refreshToken,
        ]);

        $this->accessTokenService->refreshToken(
            TokenIdentifier::fromString($accessToken),
            TokenIdentifier::fromString($refreshToken)
        )
            ->willReturn(AccessTokenFixture::accessToken());

        $response = $this->controller->refresh($this->request->reveal());

        $responseData = json_decode($response->getBody()->getContents(), true);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals(201, $response->getStatusCode());
        self::assertEquals('ok', $responseData['status']);
        self::assertArrayHasKey('accessToken', $responseData['data']);
        self::assertArrayHasKey('expires', $responseData['data']);
        self::assertArrayHasKey('refreshToken', $responseData['data']);
    }

    /**
     * @param Throwable $exception
     * @param int       $expectedStatusCode
     *
     * @dataProvider _refreshTokenExceptionDataProvider
     */
    public function test_Refresh_ShouldReturnProperStatusCode_WhenRefreshingTokenDoesNotSucceed(
        Throwable $exception,
        int $expectedStatusCode
    ) {
        $accessToken = AccessTokenFixture::randomTokenIdentifier()->toString();
        $refreshToken = AccessTokenFixture::randomTokenIdentifier()->toString();

        $this->request->getAttribute('token', '')->willReturn($accessToken);
        $this->request->getParsedBody()->willReturn([
            'refresh' => $refreshToken,
        ]);

        $this->accessTokenService->refreshToken(
            TokenIdentifier::fromString($accessToken),
            TokenIdentifier::fromString($refreshToken)
        )
            ->willThrow($exception);

        $response = $this->controller->refresh($this->request->reveal());

        self::assertEquals($expectedStatusCode, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_Retrieve_ShouldReturn400_WhenTokenWithInvalidFormatIsPassed()
    {
        $this->request->getAttribute('token', '')->willReturn('some-wrong-identifier');

        $response = $this->controller->retrieve($this->request->reveal());
        $responseData = json_decode($response->getBody()->getContents(), true);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals(400, $response->getStatusCode());
        self::assertEquals('error', $responseData['status']);
    }

    /**
     * @return void
     */
    public function test_Retrieve_ShouldReturn404_WhenTokenDoesNotExist()
    {
        $this->request->getAttribute('token', '')->willReturn(AccessTokenFixture::tokenIdentifier()->toString());
        $this->accessTokenService->getAccessTokenByIdentifier(AccessTokenFixture::tokenIdentifier())
            ->willThrow(new AccessTokenNotFoundException);

        $response = $this->controller->retrieve($this->request->reveal());
        $responseData = json_decode($response->getBody()->getContents(), true);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals(404, $response->getStatusCode());
        self::assertEquals('error', $responseData['status']);
    }

    /**
     * @return void
     */
    public function test_Retrieve_ShouldReturn500_WhenTokenDoesNotExist()
    {
        $this->request->getAttribute('token', '')->willReturn(AccessTokenFixture::tokenIdentifier()->toString());
        $this->accessTokenService->getAccessTokenByIdentifier(AccessTokenFixture::tokenIdentifier())
            ->willThrow(new UnableToRetrieveAccessTokenException());

        $response = $this->controller->retrieve($this->request->reveal());
        $responseData = json_decode($response->getBody()->getContents(), true);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals(500, $response->getStatusCode());
        self::assertEquals('error', $responseData['status']);
    }

    /**
     * @return void
     */
    public function test_Retrieve_ShouldReturnAccessToken_WhenItExists()
    {
        $accessToken = AccessTokenFixture::accessToken();

        $this->request->getAttribute('token', '')->willReturn(AccessTokenFixture::tokenIdentifier()->toString());
        $this->accessTokenService->getAccessTokenByIdentifier(AccessTokenFixture::tokenIdentifier())
            ->willReturn($accessToken);

        $response = $this->controller->retrieve($this->request->reveal());
        $responseData = json_decode($response->getBody()->getContents(), true);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('ok', $responseData['status']);
        self::assertEquals('2018-10-12T12:34:56+00:00', $responseData['data']['expires']);
    }

    /**
     * @return void
     */
    public function test_Revoke_ShouldReturn204Status_WhenAccessTokenIsDeleted()
    {
        $token = AccessTokenFixture::randomTokenIdentifier()->toString();

        $this->request->getAttribute('token', '')->willReturn($token);

        $this->accessTokenService->expireToken(TokenIdentifier::fromString($token))
            ->shouldBeCalled();

        $response = $this->controller->revoke($this->request->reveal());

        self::assertEquals(204, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_Revoke_ShouldReturn400Error_WhenIncorrectTokenIsProvided()
    {
        $this->request->getAttribute('token', '')->willReturn('test');

        $response = $this->controller->revoke($this->request->reveal());

        self::assertEquals(400, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_Revoke_ShouldReturn500Error_WhenAccessTokenCannotBeRevoked()
    {
        $token = AccessTokenFixture::randomTokenIdentifier()->toString();

        $this->request->getAttribute('token', '')->willReturn($token);

        $this->accessTokenService->expireToken(TokenIdentifier::fromString($token))
            ->willThrow(new UnableToExpireAccessTokenException);

        $response = $this->controller->revoke($this->request->reveal());

        self::assertEquals(500, $response->getStatusCode());
    }
}
