<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Middleware;

use GuldenWallet\Backend\Application\Helper\Constant\Constant;
use GuldenWallet\Backend\Application\Helper\Constant\GlobalConstant;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use Whoops\Run;
use Zend\Diactoros\Response\JsonResponse;

class ExceptionHandlingMiddleware
{
    /** @var Run */
    private $whoopsExceptionHandler;

    /**
     * @param Run $whoopsExceptionHandler
     */
    public function __construct(Run $whoopsExceptionHandler)
    {
        $this->whoopsExceptionHandler = $whoopsExceptionHandler;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     *
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        if (GlobalConstant::readUnsafe(Constant::ENVIRONMENT) === 'development') {
            $this->whoopsExceptionHandler->register();

            return $next($request, $response);
        }

        try {
            return $next($request, $response);
        } catch (Throwable $exception) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'An error has occurred while processing this request'
            ], 500);
        }
    }
}
