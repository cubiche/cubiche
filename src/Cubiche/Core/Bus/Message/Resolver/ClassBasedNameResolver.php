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

use Cubiche\Core\Bus\MessageInterface;

/**
 * ClassBasedNameResolver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ClassBasedNameResolver implements MessageNameResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolve(MessageInterface $message): string
    {
        return get_class($message);
    }
}
