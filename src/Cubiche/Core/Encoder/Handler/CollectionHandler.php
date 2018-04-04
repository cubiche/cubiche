<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Encoder\Handler;

use Cubiche\Core\Collections\ArrayCollection\ArrayHashMap;
use Cubiche\Core\Collections\ArrayCollection\ArrayList;
use Cubiche\Core\Collections\ArrayCollection\ArraySet;
use Cubiche\Core\Collections\ArrayCollection\SortedArrayHashMap;
use Cubiche\Core\Collections\ArrayCollection\SortedArrayList;
use Cubiche\Core\Collections\ArrayCollection\SortedArraySet;
use Cubiche\Core\Collections\CollectionInterface;
use Cubiche\Core\Encoder\Context\ContextInterface;
use Cubiche\Core\Encoder\Visitor\DeserializationVisitor;
use Cubiche\Core\Encoder\Visitor\SerializationVisitor;

/**
 * CollectionHandler class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CollectionHandler implements HandlerSubscriberInterface
{
    /**
     * @param SerializationVisitor $visitor
     * @param CollectionInterface  $collection
     * @param array                $type
     * @param ContextInterface     $context
     *
     * @return mixed
     */
    public function serialize(
        SerializationVisitor $visitor,
        CollectionInterface $collection,
        array $type,
        ContextInterface $context
    ) {
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
    public function deserialize(DeserializationVisitor $visitor, array $data, array $type, ContextInterface $context)
    {
        return new $type['name']($visitor->visitArray($data, $type, $context));
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedHandlers()
    {
        return array(
            'serializers' => array(
                ArraySet::class => 'serialize',
                ArrayList::class => 'serialize',
                ArrayHashMap::class => 'serialize',
                SortedArraySet::class => 'serialize',
                SortedArrayList::class => 'serialize',
                SortedArrayHashMap::class => 'serialize',
            ),
            'deserializers' => array(
                ArraySet::class => 'deserialize',
                ArrayList::class => 'deserialize',
                ArrayHashMap::class => 'deserialize',
                SortedArraySet::class => 'deserialize',
                SortedArrayList::class => 'deserialize',
                SortedArrayHashMap::class => 'deserialize',
            ),
        );
    }
}
