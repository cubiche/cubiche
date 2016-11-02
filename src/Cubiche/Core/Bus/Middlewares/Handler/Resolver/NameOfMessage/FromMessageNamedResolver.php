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

use Cubiche\Core\Bus\MessageInterface;
use Cubiche\Core\Bus\MessageNamedInterface;

/**
 * FromMessageNamedResolver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class FromMessageNamedResolver implements ResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolve(MessageInterface $message)
    {
        if ($message instanceof MessageNamedInterface) {
            return $message->named();
        }

        throw new \InvalidArgumentException(sprintf(
            'The object of type %s should implement the %s interface',
            get_class($message),
            MessageNamedInterface::class
        ));
    }
}
