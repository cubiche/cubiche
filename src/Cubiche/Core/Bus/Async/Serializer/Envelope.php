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

use Cubiche\Core\Bus\Command\CommandInterface;
use Cubiche\Core\Bus\MessageInterface;
use Cubiche\Core\Bus\Query\QueryInterface;
use Cubiche\Core\EventBus\Event\EventInterface;
use Cubiche\Domain\System\DateTime\DateTime;
use Cubiche\Domain\System\StringLiteral;

/**
 * Envelope class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Envelope implements EnvelopeInterface
{
    /**
     * @var EnvelopeId
     */
    private $id;

    /**
     * @var StringLiteral
     */
    private $messageName;

    /**
     * @var array
     */
    private $metadata = [];

    /**
     * @var MessageInterface
     */
    private $payload;

    /**
     * Envelope constructor.
     *
     * @param MessageInterface $message
     */
    public function __construct(MessageInterface $message)
    {
        $this->id = EnvelopeId::next();

        $this->payload = $message;
        $this->setMetadataValue('messageClassName', get_class($message));
        $this->setMetadataValue('envelopeType', $this->messageType($message));
        $this->setMetadataValue('occurredOn', DateTime::now());
    }

    public function id(): EnvelopeId
    {
        return $this->id;
    }

    public function metadata(): array
    {
        return $this->metadata;
    }

    public function payload(): MessageInterface
    {
        return $this->payload;
    }

    public function setMessageName(string $messageName): void
    {
        $this->messageName = $messageName;
    }

    public function messageName(): string
    {
        if ($this->messageName !== null) {
            return $this->messageName;
        }

        return get_class($this->payload);
    }

    public function setMetadataValue(string $key, $value): void
    {
        $this->metadata[$key] = $value;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getMetadataValue(string $key)
    {
        if (isset($this->metadata[$key])) {
            return $this->metadata[$key];
        }

        return null;
    }

    public function envelopeType(): EnvelopeType
    {
        return $this->getMetadataValue('envelopeType');
    }

    public function messageClassName(): string
    {
        return $this->getMetadataValue('messageClassName');
    }

    public function occurredOn(): DateTime
    {
        return $this->getMetadataValue('occurredOn');
    }

    private function messageType(MessageInterface $message): EnvelopeType
    {
        if ($message instanceof CommandInterface) {
            return EnvelopeType::COMMAND();
        }

        if ($message instanceof QueryInterface) {
            return EnvelopeType::QUERY();
        }

        if ($message instanceof EventInterface) {
            return EnvelopeType::EVENT();
        }

        return EnvelopeType::MESSAGE();
    }
}
