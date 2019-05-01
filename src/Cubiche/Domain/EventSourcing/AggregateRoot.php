<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing;

use Cubiche\Core\Bus\Message\Recorder\MessageRecorder;
use Cubiche\Core\Delegate\Delegate;
use Cubiche\Core\Validator\Validator;
use Cubiche\Domain\EventSourcing\EventStore\EventStream;
use Cubiche\Domain\Model\Entity;

/**
 * Abstract aggregate root class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class AggregateRoot extends Entity implements AggregateRootInterface
{
    use MessageRecorder;

    /**
     * @var int
     */
    protected $version = 0;

    /**
     * @var bool
     */
    private $isRunning;

    /**
     * @var Delegate[]
     */
    private $queue = [];

    /**
     * @param DomainEventInterface $event
     *
     * @throws \Exception
     */
    protected function recordAndApplyEvent(DomainEventInterface $event)
    {
        // The applyEvent method can trigger another event and all must be applied in the same order
        // Using a queue, we avoid that the events are applied in a disorderly way
        $this->queue[] = Delegate::fromClosure(function () use ($event) {
            Validator::assert($event);

            $this->version = $this->version + 1;

            $event->setVersion($this->version());

            $this->recordMessage($event);
            $this->applyEvent($event);
        });

        if ($this->isRunning) {
            return;
        }

        $this->isRunning = true;
        try {
            $this->runQueuedJobs();
        } catch (\Exception $e) {
            $this->isRunning = false;
            $this->queue = [];

            throw $e;
        }

        $this->isRunning = false;
    }

    /**
     * Process any pending message in the queue.
     */
    private function runQueuedJobs()
    {
        while ($lastEvent = array_shift($this->queue)) {
            $lastEvent->__invoke();
        }
    }

    /**
     * @param DomainEventInterface $event
     */
    protected function applyEvent(DomainEventInterface $event)
    {
        $classParts = explode('\\', get_class($event));
        $method = 'apply'.end($classParts);

        if (!method_exists($this, $method)) {
            throw new \BadMethodCallException(
                "There is no method named '$method' that can be applied to '".get_class($this)."'. "
            );
        }

        $this->$method($event);

        $this->setVersion($event->version());
    }

    /**
     * @param EventStream $history
     *
     * @return AggregateRootInterface
     */
    public static function loadFromHistory(EventStream $history)
    {
        $reflector = new \ReflectionClass(static::class);

        /** @var AggregateRootInterface $aggregateRoot */
        $aggregateRoot = $reflector->newInstanceWithoutConstructor();
        $aggregateRoot->id = $history->streamName()->id();
        $aggregateRoot->replay($history);

        return $aggregateRoot;
    }

    /**
     * @param EventStream $history
     */
    public function replay(EventStream $history)
    {
        foreach ($history as $event) {
            $this->applyEvent($event);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function version()
    {
        return $this->version;
    }

    /**
     * {@inheritdoc}
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }
}
