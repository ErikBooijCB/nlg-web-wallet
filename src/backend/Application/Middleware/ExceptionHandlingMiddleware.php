<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Middleware;

use GuldenWallet\Backend\Application\Helper\Constant\Constant;
use GuldenWallet\Backend\Application\Helper\Constant\GlobalConstant;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use Zend\Diactoros\Response\JsonResponse;

class ExceptionHandlingMiddleware
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     *
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        try {
            return $next($request, $response);
        } catch (Throwable $exception) {
            $responseData = [
                'status' => 'error',
                'message' => 'An error has occurred while processing this request'
            ];

            if (GlobalConstant::readUnsafe(Constant::ENVIRONMENT) === 'development') {
                $responseData['error'] = $exception->getMessage();
            }

            return new JsonResponse($responseData, 500);
        }
    }
}
