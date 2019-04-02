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

use Cubiche\Core\Collections\CollectionInterface;
use Cubiche\Core\Serializer\Context\ContextInterface;
use Cubiche\Core\Serializer\Visitor\DeserializationVisitor;
use Cubiche\Core\Serializer\Visitor\SerializationVisitor;

/**
 * CollectionHandler class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CollectionHandler implements HandlerInterface
{
    /**
     * @param SerializationVisitor $visitor
     * @param CollectionInterface  $collection
     * @param array                $type
     * @param ContextInterface     $context
     *
     * @return mixed
     */
    public function serialize(SerializationVisitor $visitor, $collection, array $type, ContextInterface $context)
    {
        return $visitor->visitArray($collection->toArray(), $type, $context);
    }

    /**
     * @param DeserializationVisitor $visitor
     * @param array                  $data
     * @param array                  $type
     * @param ContextInterface       $context
     *
     * @return mixed
     */
    public function deserialize(DeserializationVisitor $visitor, $data, array $type, ContextInterface $context)
    {
        return new $type['name']($visitor->visitArray($data, $type, $context));
    }

    /**
     * {@inheritdoc}
     */
    public function supports($typeName, ContextInterface $context)
    {
        return \is_subclass_of($typeName, CollectionInterface::class);
    }

    /**
     * {@inheritdoc}
     */
    public function order()
    {
        return 50;
    }
}
