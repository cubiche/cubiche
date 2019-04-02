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

/**
 * Handler interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface HandlerInterface
{
    /**
     * @param SerializationVisitor $visitor
     * @param mixed                $data
     * @param array                $type
     * @param ContextInterface     $context
     *
     * @return mixed
     */
    public function serialize(SerializationVisitor $visitor, $data, array $type, ContextInterface $context);

    /**
     * @param DeserializationVisitor $visitor
     * @param array                  $data
     * @param array                  $type
     * @param ContextInterface       $context
     *
     * @return mixed
     */
    public function deserialize(DeserializationVisitor $visitor, $data, array $type, ContextInterface $context);

    /**
     * Checks whether the given type is supported for serialize/deserialize by this handler.
     *
     * @param string           $typeName
     * @param ContextInterface $context
     *
     * @return boolean
     */
    public function supports($typeName, ContextInterface $context);

    /**
     * @return integer
     */
    public function order();
}
