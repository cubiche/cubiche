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

/**
 * HandlerSubscriber interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface HandlerSubscriberInterface
{
    /**
     * Returns an array of type names this subscriber wants to listen to.
     * The array keys are type names and the value can be the method name to call.
     *
     * For instance:
     *
     *  array(
     *     'serializers' => array('typeName' => 'methodName'),
     *     'deserializers' => array('typeName' => 'methodName')
     *  )
     *
     * @return array The type names to listen to
     */
    public static function getSubscribedHandlers();
}
