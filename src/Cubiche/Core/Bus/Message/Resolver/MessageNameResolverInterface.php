<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus\Message\Resolver;

use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Bus\MessageInterface;
use InvalidArgumentException;

/**
 * MessageNameResolver interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface MessageNameResolverInterface
{
    /**
     * Resolve the name of message for a given message.
     *
     * @throws InvalidArgumentException
     * @throws NotFoundException
     */
    public function resolve(MessageInterface $message): string ;
}
