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
use Cubiche\Domain\System\DateTime\Date;
use Cubiche\Domain\System\DateTime\DateTime;

/**
 * DateTimeValueObjectHandler class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DateTimeValueObjectHandler implements HandlerInterface
{
    /**
     * @param SerializationVisitor       $visitor
     * @param NativeValueObjectInterface $date
     * @param array                      $type
     * @param ContextInterface           $context
     *
     * @return mixed
     */
    public function serialize(SerializationVisitor $visitor, $date, array $type, ContextInterface $context)
    {
        $newType = array('name' => \DateTime::class, 'params' => array());

        return $context->navigator()->accept($date->toNative(), $newType, $context);
    }

    /**
     * @param DeserializationVisitor $visitor
     * @param \DateTime              $data
     * @param array                  $type
     * @param ContextInterface       $context
     *
     * @return mixed
     */
    public function deserialize(DeserializationVisitor $visitor, $data, array $type, ContextInterface $context)
    {
        $newType = array('name' => \DateTime::class, 'params' => array());

        return $type['name']::fromNative($context->navigator()->accept($data, $newType, $context));
    }

    /**
     * {@inheritdoc}
     */
    public function supports($typeName, ContextInterface $context)
    {
        return \is_a($typeName, DateTime::class, true) || \is_a($typeName, Date::class, true);
    }

    /**
     * {@inheritdoc}
     */
    public function order()
    {
        return 250;
    }
}
