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
use Cubiche\Domain\Model\NativeValueObjectInterface;

/**
 * NativeValueObjectHandler class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class NativeValueObjectHandler implements HandlerInterface
{
    /**
     * @param SerializationVisitor       $visitor
     * @param NativeValueObjectInterface $valueObject
     * @param array                      $type
     * @param ContextInterface           $context
     *
     * @return mixed
     */
    public function serialize(SerializationVisitor $visitor, $valueObject, array $type, ContextInterface $context)
    {
        return $context->navigator()->accept($valueObject->toNative(), null, $context);
    }

    /**
     * @param DeserializationVisitor $visitor
     * @param mixed                  $data
     * @param array                  $type
     * @param ContextInterface       $context
     *
     * @return mixed
     */
    public function deserialize(DeserializationVisitor $visitor, $data, array $type, ContextInterface $context)
    {
        return $type['name']::fromNative($data);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($typeName, ContextInterface $context)
    {
        return \is_subclass_of($typeName, NativeValueObjectInterface::class);
    }

    /**
     * {@inheritdoc}
     */
    public function order()
    {
        return 350;
    }
}
