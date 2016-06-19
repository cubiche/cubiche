<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus\Middlewares\Handler\Resolver\NameOfMessage;

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
     * Resolve the name of message for a given message.
     *
     * @param MessageInterface $message
     *
     * @return string
     *
     * @throws InvalidArgumentException
     * @throws NotFoundException
     */
    public function resolve(MessageInterface $message);
}
