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
     *
     * @param string|null $eventName
     */
    public function __construct($eventName = null)
    {
        parent::__construct($eventName);

        $this->setMetadata('occurredOn', new DateTime());
        $this->setMetadata('eventType', parent::eventName());
        $this->setMetadata('propagationStopped', parent::isPropagationStopped());
    }

    /**
     * @return DateTime
     */
    public function occurredOn()
    {
        return $this->getMetadata('occurredOn');
    }

    /**
     * {@inheritdoc}
     */
    public function eventName()
    {
        return $this->getMetadata('eventType');
    }

    /**
     * {@inheritdoc}
     */
    public function stopPropagation()
    {
        $this->setMetadata('propagationStopped', true);
    }

    /**
     * {@inheritdoc}
     */
    public function isPropagationStopped()
    {
        return $this->getMetadata('propagationStopped');
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
            'metadata' => $this->metadata,
            'payload' => $this->payload,
        );
    }
}
