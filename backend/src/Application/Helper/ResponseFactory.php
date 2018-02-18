<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Helper;

use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

class ResponseFactory
{
    /**
     * @param array $data
     * @param int   $statusCode
     *
     * @return ResponseInterface
     */
    public static function success(array $data, $statusCode = 200): ResponseInterface
    {
        return new JsonResponse([
            'status' => 'ok',
            'data'   => $data,
        ], $statusCode);
    }

    /**
     * @param string $message
     * @param int    $statusCode
     *
     * @return ResponseInterface
     */
    public static function failure(string $message, int $statusCode = 500): ResponseInterface
    {
        return new JsonResponse([
            'status' => 'error',
            'message' => $message
        ], $statusCode);
    }
}
