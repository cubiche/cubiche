<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Handler\Resolver;

use Cubiche\Core\Bus\Exception\InvalidResolverException;
use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Bus\MessageInterface;

/**
 * Resolver interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface MessageHandlerResolverInterface
{
    /**
     * Resolve the handler class of a given message.
     *
     * @throws NotFoundException
     */
    public function resolve(MessageInterface $message): callable;
}
