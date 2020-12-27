<?php

/**
 * This file is part of the crs/k8s library.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Crs\K8s\Http;

use Crs\K8s\Http\Exception\HttpException;
use Crs\K8s\Serialization\Serializer;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;

class HttpClient
{
    public const CONTENT_TYPE_JSON = 'application/json';

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * @var ResponseHandlerFactory
     */
    private $handlerFactory;

    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(
        RequestFactory $requestFactory,
        ClientInterface $client,
        Serializer $serializer,
        ?ResponseHandlerFactory $handlerFactory = null
    ) {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->serializer = $serializer;
        $this->handlerFactory = $handlerFactory ?? new ResponseHandlerFactory();
    }

    /**
     * @param array|object $options
     * @return mixed
     * @throws HttpException
     */
    public function send(string $uri, string $action, $options)
    {
        $model = $options['model'] ?? null;
        $body = $options['body'] ?? null;

        if ($body) {
            $body = $this->serializer->serialize($body);
        }

        try {
            $request = $this->requestFactory->makeRequest(
                $uri,
                $action,
                $model ? RequestFactory::CONTENT_TYPE_JSON : null,
                $body
            );

            $response = $this->client->sendRequest($request);
            $responseHandlers = $this->handlerFactory->makeHandlers($this->serializer);

            foreach ($responseHandlers as $responseHandler) {
                if ($responseHandler->supports($response, $options)) {
                    return $responseHandler->handle($response, $options);
                }
            }

            throw new HttpException(
                'There was no supported handler found for the API response.',
                $response->getStatusCode()
            );
        } catch (ClientExceptionInterface $exception) {
            throw new HttpException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }
}
