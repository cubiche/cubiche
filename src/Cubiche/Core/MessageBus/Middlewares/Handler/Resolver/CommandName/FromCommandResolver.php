<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\MessageBus\Middlewares\Handler\Resolver\CommandName;

use Cubiche\Core\MessageBus\Command\CommandInterface;
use Cubiche\Core\MessageBus\CommandNamedInterface;

/**
 * FromCommandResolver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class FromCommandResolver implements ResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolve(CommandInterface $command)
    {
        if ($command instanceof CommandNamedInterface) {
            return $command->name();
        }

        throw new \InvalidArgumentException(sprintf(
            'The command of type %s should implement the %s interface',
            get_class($command),
            CommandNamedInterface::class
        ));
    }
}
