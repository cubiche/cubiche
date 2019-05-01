<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus\Handler\MethodName;

use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Bus\MessageInterface;

/**
 * HandlerMethodNameResolver interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface HandlerMethodNameResolverInterface
{
    /**
     * Resolve the handler method name for a given message.
     *
     * @throws NotFoundException
     */
    public function resolve(MessageInterface $message): string;
}
