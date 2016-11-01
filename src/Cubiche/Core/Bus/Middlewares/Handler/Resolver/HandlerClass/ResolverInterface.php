<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerClass;

use Cubiche\Core\Bus\Exception\InvalidResolverException;
use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Bus\MessageInterface;
use InvalidArgumentException;

/**
 * Resolver interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface ResolverInterface
{
    /**
     * Resolve the handler class from a given message.
     *
     * @param MessageInterface $message
     *
     * @return callable
     *
     * @throws InvalidArgumentException
     * @throws NotFoundException
     * @throws InvalidResolverException
     */
    public function resolve(MessageInterface $message);

    /**
     * @param string $className
     * @param mixed  $handler
     */
    public function addHandler($className, $handler);

    /**
     * @param string $className
     *
     * @return object
     */
    public function getHandlerFor($className);
}
