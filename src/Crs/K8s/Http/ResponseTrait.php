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

use Psr\Http\Message\ResponseInterface;

trait ResponseTrait
{
    protected function isResponseSuccess(ResponseInterface $response): bool
    {
        return $response->getStatusCode() >= 200 && $response->getStatusCode() <= 299;
    }

    protected function isResponseError(ResponseInterface $response): bool
    {
        return $response->getStatusCode() >= 400 && $response->getStatusCode() <= 500;
    }

    protected function isResponseContentType(ResponseInterface $response, string $contentType): bool
    {
        $contentTypes = $response->getHeader('content-type');

        return in_array($contentType, $contentTypes, true);
    }
}
