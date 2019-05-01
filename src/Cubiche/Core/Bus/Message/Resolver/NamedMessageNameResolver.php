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
use Cubiche\Core\Bus\NamedMessageInterface;

/**
 * NamedMessageNameResolver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class NamedMessageNameResolver implements MessageNameResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolve(MessageInterface $message): string
    {
        if (!($message instanceof NamedMessageInterface)) {
            throw new \InvalidArgumentException(
                sprintf('Message "%s" should be an instance of NamedMessageInterface', get_class($message))
            );
        }
        
        return $message->messageName();
    }
}
