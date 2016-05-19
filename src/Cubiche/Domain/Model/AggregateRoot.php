<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Model;

use Cubiche\Core\Validator\Validator;
use Cubiche\Core\Validator\ValidatorInterface;
use Cubiche\Domain\EventPublisher\DomainEventPublisher;
use Cubiche\Domain\Model\EventSourcing\EntityDomainEventInterface;
use Cubiche\Domain\Model\EventSourcing\EventStream;

/**
 * Abstract Aggregate Root Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class AggregateRoot implements AggregateRootInterface
{
    /**
     * @var IdInterface
     */
    protected $id;

    /**
     * @var EntityDomainEventInterface[]
     */
    private $recordedEvents = [];

    /**
     * @param IdInterface $id
     */
    protected function __construct(IdInterface $id)
    {
        $this->id = $id;
    }

    /**
     * {@inheritdoc}
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @param EntityDomainEventInterface $event
     */
    protected function recordApplyAndPublishEvent(EntityDomainEventInterface $event)
    {
        $this->recordEvent($event);
        $this->applyEvent($event);
        $this->publishEvent($event);
    }

    /**
     * @param EntityDomainEventInterface $event
     */
    protected function applyEvent(EntityDomainEventInterface $event)
    {
        $classParts = explode('\\', get_class($event));
        $method = 'apply'.end($classParts);

        if (!method_exists($this, $method)) {
            throw new \BadMethodCallException(
                "There is no method named '$method' that can be applied to '".get_class($this)."'. "
            );
        }

        $this->$method($event);
    }

    /**
     * @param EntityDomainEventInterface $event
     */
    protected function publishEvent(EntityDomainEventInterface $event)
    {
        DomainEventPublisher::publish($event);
    }

    /**
     * @param EntityDomainEventInterface $event
     */
    protected function recordEvent(EntityDomainEventInterface $event)
    {
        $this->recordedEvents[] = $event;
    }

    /**
     * @return EntityDomainEventInterface[]
     */
    public function recordedEvents()
    {
        return $this->recordedEvents;
    }

    /**
     * Clear recorded events.
     */
    public function clearEvents()
    {
        $this->recordedEvents = [];
    }

    /**
     * @param EventStream $history
     *
     * @return AggregateRootInterface
     */
    public static function loadFromHistory(EventStream $history)
    {
        $aggregateRoot = new static($history->aggregateId());
        $aggregateRoot->replay($history);

        return $aggregateRoot;
    }

    /**
     * @param EventStream $history
     */
    public function replay(EventStream $history)
    {
        if (get_class($this) !== $history->className()) {
            throw new \InvalidArgumentException(
                sprintf(
                    'You cannot replay an AggregateRoot of type %s from an EventStream for object of type %s',
                    get_class($this),
                    $history->className()
                )
            );
        }

        foreach ($history->events() as $event) {
            $this->applyEvent($event);
        }
    }

    /**
     * Return an instance of the aggregareRoot validator.
     *
     * @return Validator
     */
    public function validator()
    {
        // e.g Cubiche\Domain\Locale\Locale
        $aggregateClass = static::class;

        $classParts = explode('\\', $aggregateClass);
        $className = end($classParts);
        $prefix = substr($aggregateClass, 0, strpos($aggregateClass, $className));

        $classNames = [
            // e.g. Cubiche\Domain\Locale\LocaleValidator
            $aggregateClass.'Validator',
            // e.g. Cubiche\Domain\Locale\Validator\LocaleValidator
            $prefix.'Validator\\'.$className.'Validator',
        ];

        foreach ($classNames as $className) {
            if (class_exists($className)) {
                $reflection = new \ReflectionClass($className);
                if (!$reflection->implementsInterface(ValidatorInterface::class)) {
                    throw new \InvalidArgumentException(sprintf(
                        'The class %s must be instance of %s',
                        $className,
                        ValidatorInterface::class
                    ));
                }

                return $className::create();
            }
        }

        return Validator::create();
    }

    /**
     * {@inheritdoc}
     */
    public function equals($other)
    {
        return $other instanceof static && $this->id()->equals($other->id());
    }
}
