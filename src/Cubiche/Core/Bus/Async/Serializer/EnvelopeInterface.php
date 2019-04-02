<?php

/**
 * This file is part of the Cubiche/Bus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Async\Serializer;

/**
 * Envelope interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface EnvelopeInterface
{
    /**
     * @return string
     */
    public function messageName(): string;

    /**
     * @return string
     */
    public function messageType(): string;

    /**
     * @return array
     */
    public function serializedMessage(): array;

    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @param array $data
     *
     * @return EnvelopeInterface
     */
    public static function fromArray(array $data): EnvelopeInterface;
}
