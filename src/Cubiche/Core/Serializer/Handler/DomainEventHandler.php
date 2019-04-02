<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Serializer\Handler;

use Cubiche\Core\Serializer\Context\ContextInterface;
use Cubiche\Core\Serializer\Visitor\DeserializationVisitor;
use Cubiche\Core\Serializer\Visitor\SerializationVisitor;
use Cubiche\Domain\EventSourcing\DomainEventInterface;

/**
 * DomainEventHandler class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DomainEventHandler implements HandlerSubscriberInterface
{
    /**
     * @param SerializationVisitor $visitor
     * @param DomainEventInterface $event
     * @param array                $type
     * @param ContextInterface     $context
     *
     * @return mixed
     */
    public function serialize(
        SerializationVisitor $visitor,
        DomainEventInterface $event,
        array $type,
        ContextInterface $context
    ) {
        return $visitor->visitArray($event->toArray(), $type, $context);
    }

    /**
     * @param DeserializationVisitor $visitor
     * @param array                  $data
     * @param array                  $type
     * @param ContextInterface       $context
     *
     * @return mixed
     */
    public function deserialize(DeserializationVisitor $visitor, array $data, array $type, ContextInterface $context)
    {
        return $type['name']::fromArray($visitor->visitArray($data, $type, $context));
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedHandlers()
    {
        return array(
            'serializers' => array(
                'UserWasCreated' => 'serialize'
            ),
            'deserializers' => array(
                'UserWasCreated' => 'deserialize'
            ),
        );
    }
}
