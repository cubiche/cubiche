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
use Cubiche\Domain\System\DateTime\Date;
use Cubiche\Domain\System\DateTime\DateRange;

/**
 * DateRangeHandler class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DateRangeHandler implements HandlerSubscriberInterface
{
    /**
     * @param SerializationVisitor $visitor
     * @param DateRange            $range
     * @param array                $type
     * @param ContextInterface     $context
     *
     * @return mixed
     */
    public function serialize(
        SerializationVisitor $visitor,
        DateRange $range,
        array $type,
        ContextInterface $context
    ) {
        $newType = array('name' => Date::class, 'params' => array());

        return array(
            'from' => $context->navigator()->accept($range->from(), $newType, $context),
            'to' => $context->navigator()->accept($range->to(), $newType, $context),
        );
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
        $newType = array('name' => Date::class, 'params' => array());
        $from = $context->navigator()->accept($data['from'], $newType, $context);
        $to = $context->navigator()->accept($data['to'], $newType, $context);

        return new $type['name']($from, $to);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedHandlers()
    {
        return array(
            'serializers' => array(
                DateRange::class => 'serialize',
            ),
            'deserializers' => array(
                DateRange::class => 'deserialize',
            ),
        );
    }
}
