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

use Cubiche\Core\Bus\MessageInterface;
use Cubiche\Core\Bus\NamedMessageInterface;
use Cubiche\Domain\System\DateTime\DateTime;
use Cubiche\Domain\System\StringLiteral;

/**
 * Envelope interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface EnvelopeInterface extends NamedMessageInterface
{
    public function id(): EnvelopeId;
    public function metadata(): array;
    public function payload(): MessageInterface;
    public function setMessageName(string $messageName): void;
    public function messageName(): string ;

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function setMetadataValue(string $key, $value): void;

    /**
     * @return mixed
     */
    public function getMetadataValue(string $key);
    public function envelopeType(): EnvelopeType;
    public function messageClassName(): string;
    public function occurredOn(): DateTime;
}
