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
     * Gets the handler of a specific type.
     *
     * @param string           $typeName
     * @param ContextInterface $context
     *
     * @return HandlerInterface|null
     */
    public function handler($typeName, ContextInterface $context);

    /**
     * Add a new handler
     *
     * @param HandlerInterface $handler
     */
    public function addHandler(HandlerInterface $handler);
}
