<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Infrastructure\Node;

use Psr\Http\Message\ResponseInterface;
use Throwable;

class NodeResponse
{
    /** @var string */
    const FAILURE = 'failure';

    /** @var string */
    const SUCCESS = 'success';

    /** @var array */
    private $data = [];

    /** @var string */
    private $errorInfo = '';

    /** @var string */
    private $status = '';

    /**
     * Controller is private to enforce creation through factory methods
     */
    private function __construct()
    {
    }

    /**
     * @param ResponseInterface $response
     * @return NodeResponse
     */
    public static function fromPsrResponse(ResponseInterface $response): self
    {
        $data = json_decode($response->getBody()->getContents(), true)['result'];

        $nodeResponse = new static;

        $nodeResponse->status = static::SUCCESS;
        $nodeResponse->data = is_array($data) ? $data : [$data];

        return $nodeResponse;
    }

    /**
     * @param Throwable $throwable
     * @return NodeResponse
     */
    public static function forFailedRequest(Throwable $throwable): self
    {
        $nodeResponse = new static;

        $nodeResponse->errorInfo = $throwable->getMessage();
        $nodeResponse->status = static::FAILURE;

        return $nodeResponse;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getErrorInfo(): string
    {
        return $this->errorInfo;
    }

    /**
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return $this->status === static::SUCCESS;
    }
}
