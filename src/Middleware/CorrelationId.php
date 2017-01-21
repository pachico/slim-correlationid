<?php

namespace Pachico\SlimCorrelationId\Middleware;

use \Pachico\SlimCorrelationId\Generator;
use \Pachico\SlimCorrelationId\Model;
use \Psr\Http\Message;

class CorrelationId
{

    /**
     * @var callable
     */
    private $callable;

    /**
     * @var array
     */
    private $settings;

    /**
     * @var Generator\IdInterface
     */
    private $idGenerator;

    /**
     * @param array $settings
     * @param callable $callable Which will be called as soon as the correlation id is resolved
     * @param Generator\IdInterface $idGenerator
     */
    public function __construct(
        array $settings = [],
        callable $callable = null,
        Generator\IdInterface $idGenerator = null
    ) {
        $this->settings = array_merge([
            'header_key' => Model\CorrelationId::DEFAULT_HEADER_KEY
            ], $settings);

        $this->callable = $callable;
        $this->idGenerator = $idGenerator ?: new Generator\Simple();
    }

    /**
     * @param Message\RequestInterface $request
     * @param Message\ResponseInterface $response
     * @param callable $next
     *
     * @return Message\ResponseInterface
     */
    public function __invoke(Message\RequestInterface $request, Message\ResponseInterface $response, callable $next)
    {
        $correlationId = $this->resolveCorrelationId($request);
        if ($this->callable) {
            call_user_func_array($this->callable, [$correlationId]);
        }
        $requestWithHeader = $request->withHeader($this->settings['header_key'], (string) $correlationId);
        $pipeThroughResponse = $next($requestWithHeader, $response);
        $responseWithHeader = $pipeThroughResponse->withHeader($this->settings['header_key'], (string) $correlationId);
        
        return $responseWithHeader;
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     *
     * @return Model\IdInterface
     */
    private function resolveCorrelationId(Message\RequestInterface $request)
    {
        $headerLine = $request->getHeaderLine($this->settings['header_key']);
        
        if (!empty($headerLine)) {
            return new Model\CorrelationId($headerLine);
        }

        return Model\CorrelationId::create($this->idGenerator);
    }
}
