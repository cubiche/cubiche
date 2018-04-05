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

/**
 * HandlerManager class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface HandlerManagerInterface
{
    /**
     * @param HandlerSubscriberInterface $subscriberHandler
     */
    public function registerSubscriberHandler(HandlerSubscriberInterface $subscriberHandler);

    /**
     * Gets the handler of a specific type.
     *
     * @param string           $typeName
     * @param ContextInterface $context
     *
     * @return callable|null
     */
    public function handler($typeName, ContextInterface $context);

    /**
     * Checks whether a type has any registered handler.
     *
     * @param string           $typeName
     * @param ContextInterface $context
     *
     * @return bool
     */
    public function hasHandler($typeName, ContextInterface $context);
}
