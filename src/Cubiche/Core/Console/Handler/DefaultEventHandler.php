<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Console\Handler;

use Cubiche\Core\Bus\Event\EventInterface;
use Cubiche\Core\Bus\Event\EventSubscriberInterface;
use Cubiche\Core\EventDispatcher\PostDispatchEvent;
use Cubiche\Core\EventDispatcher\PreDispatchEvent;
use Webmozart\Console\Api\IO\IO;
use Webmozart\Console\UI\Component\Table;
use Webmozart\Console\UI\Style\TableStyle;

/**
 * DefaultEventHandler class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DefaultEventHandler implements EventSubscriberInterface, EventHandlerInterface
{
    /**
     * @var callback
     */
    protected $preDispatchHandler;

    /**
     * @var callback
     */
    protected $postDispatchHandler;

    /**
     * @var IO
     */
    protected $io;

    /**
     * @param IO $io
     */
    public function setIo(IO $io)
    {
        $this->io = $io;
    }

    /**
     * @param callable $handler
     *
     * @return $this
     */
    public function addPreDispatchEventHandler($handler)
    {
        if (!is_callable($handler)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected a callable. Got: %s',
                is_object($handler) ? get_class($handler) : gettype($handler)
            ));
        }

        $this->preDispatchHandler = $handler;

        return $this;
    }

    /**
     * @param callable $handler
     *
     * @return $this
     */
    public function addPostDispatchEventHandler($handler)
    {
        if (!is_callable($handler)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected a callable. Got: %s',
                is_object($handler) ? get_class($handler) : gettype($handler)
            ));
        }

        $this->postDispatchHandler = $handler;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearEventHandlers()
    {
        $this->preDispatchHandler = null;
        $this->postDispatchHandler = null;

        return $this;
    }

    /**
     * @param PreDispatchEvent $preDispatchEvent
     */
    public function onPreDispatchEvent(PreDispatchEvent $preDispatchEvent)
    {
        if ($this->preDispatchHandler) {
            call_user_func($this->preDispatchHandler, $preDispatchEvent->event(), $this->io);
        } else {
            $this->io->writeLine('<c1>------------------------------------------------------------------------</c1>');
            $this->io->writeLine('Dispatching <c1>'.$this->eventToString($preDispatchEvent->event()).'</c1> with:');
            $this->io->writeLine('');

            $properties = $this->objectToPropertyArray($preDispatchEvent->event());

            $table = new Table(TableStyle::asciiBorder());
            $table->setHeaderRow(array_keys($properties));
            $table->addRow(array_values($properties));

            $table->render($this->io);
            $this->io->writeLine('');
        }
    }

    /**
     * @param PostDispatchEvent $postDispatchEvent
     */
    public function onPostDispatchEvent(PostDispatchEvent $postDispatchEvent)
    {
        if ($this->postDispatchHandler) {
            call_user_func($this->postDispatchHandler, $postDispatchEvent->event(), $this->io);
        } else {
            $this->io->writeLine('<c1>'.$this->eventToString($postDispatchEvent->event()).'</c1> success!!');
            $this->io->writeLine('');
        }
    }

    /**
     * @param EventInterface $event
     *
     * @return array
     */
    protected function objectToPropertyArray(EventInterface $event)
    {
        $properties = array();
        $reflection = new \ReflectionClass(get_class($event));

        /** @var \ReflectionProperty $property */
        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);

            if (!empty($property->getValue($event))) {
                $value = $property->getValue($event);
                if ($value instanceof \DateTime) {
                    $value = $value->format(\DateTime::ISO8601);
                }

                $properties[$property->getName()] = $value;
            }
        }

        return $properties;
    }

    /**
     * @param EventInterface $event
     *
     * @return array
     */
    protected function eventToString(EventInterface $event)
    {
        $pos = strrpos($event->eventName(), '\\');
        if ($pos !== false) {
            $className = substr($event->eventName(), strrpos($event->eventName(), '\\') + 1);
        } else {
            $className = $event->eventName();
        }

        return strtolower(trim(preg_replace('([A-Z])', ' $0', $className)));
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            PreDispatchEvent::class => 'onPreDispatchEvent',
            PostDispatchEvent::class => 'onPostDispatchEvent',
        );
    }
}
