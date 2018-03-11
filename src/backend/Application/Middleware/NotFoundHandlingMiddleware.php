<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Middleware;

use GuldenWallet\Backend\Application\Helper\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class NotFoundHandlingMiddleware
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
        /** @var ResponseInterface $response */
        $response = $next($request, $response);

        $statusCode = $response->getStatusCode();

        if (in_array($statusCode, [404, 405])) {
            return ResponseFactory::failure('Route or method not available', 404);
        }

        return $response;
    }
}
