<?php

/**
 * This file is part of the k8s/client library.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace K8s\Client\Serialization\Contract;

interface DenormalizerInterface
{
    /**
     * @param class-string|null $modelFqcn
     */
    public function denormalize(array $data, ?string $modelFqcn = null): object;
}
