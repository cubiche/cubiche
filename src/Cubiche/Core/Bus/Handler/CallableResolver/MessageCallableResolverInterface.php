<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus\Handler\CallableResolver;

use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Bus\MessageInterface;

/**
 * MessageCallableResolver interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface MessageCallableResolverInterface
{
    /**
     * Resolve the message handler callable.
     *
     * @param MessageInterface       $message
     * @param string|object|callable $handler
     * @throws NotFoundException
     */
    public function resolve(MessageInterface $message, $handler): callable;
}
