<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventPublisher;

use Cubiche\Core\EventBus\Event\Event;
use DateTime;

/**
 * DomainEvent class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DomainEvent extends Event implements DomainEventInterface
{
    /**
     * @var array
     */
    private $metadata = [];

    /**
     * @var array
     */
    private $payload = [];

    /**
     * DomainEvent constructor.
     */
    public function __construct()
    {
        $this->setMetadata('occurredOn', new DateTime());
    }

    /**
     * @return DateTime
     */
    public function occurredOn()
    {
        return $this->getMetadata('occurredOn');
    }

    /**
     * @param string $property
     * @param mixed  $value
     */
    protected function setMetadata($property, $value)
    {
        $this->metadata[$property] = $value;
    }

    /**
     * @param string $property
     *
     * @return mixed
     */
    protected function getMetadata($property)
    {
        return $this->metadata[$property];
    }

    /**
     * @param string $property
     * @param mixed  $value
     */
    protected function setPayload($property, $value)
    {
        $this->payload[$property] = $value;
    }

    /**
     * @param string $property
     *
     * @return mixed
     */
    protected function getPayload($property)
    {
        return $this->payload[$property];
    }

    /**
     * {@inheritdoc}
     */
    public static function fromArray(array $data)
    {
        $reflectionClass = new \ReflectionClass(static::class);

        /** @var DomainEvent $domainEvent */
        $domainEvent = $reflectionClass->newInstanceWithoutConstructor();

        $domainEvent->eventName = $data['eventType'];
        $domainEvent->metadata = $data['metadata'];
        $domainEvent->payload = $data['payload'];

        return $domainEvent;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return array(
            'eventType' => $this->eventName(),
            'metadata' => $this->metadata,
            'payload' => $this->payload,
        );
    }
}
