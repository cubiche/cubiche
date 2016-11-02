<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Cqrs\Middlewares\Handler\Resolver\NameOfCommand;

use Cubiche\Core\Bus\MessageInterface;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\NameOfMessage\FromMessageNamedResolver;
use Cubiche\Core\Cqrs\Command\CommandNamedInterface;

/**
 * FromCommandNamedResolver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class FromCommandNamedResolver extends FromMessageNamedResolver
{
    /**
     * {@inheritdoc}
     */
    public function resolve(MessageInterface $message)
    {
        if ($message instanceof CommandNamedInterface) {
            return $message->named();
        }

        throw new \InvalidArgumentException(sprintf(
            'The object of type %s should implement the %s interface',
            get_class($message),
            CommandNamedInterface::class
        ));
    }
}
