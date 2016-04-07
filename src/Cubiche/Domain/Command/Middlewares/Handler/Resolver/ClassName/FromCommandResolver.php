<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Command\Middlewares\Handler\Resolver\ClassName;

use Cubiche\Domain\Command\CommandNameInterface;

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
    public function resolve($command)
    {
        if ($command instanceof CommandNameInterface) {
            return $command->commandName();
        }

        throw new \InvalidArgumentException(sprintf(
            'The command of type %s should implement the %s interface',
            is_object($command) ? get_class($command) : gettype($command),
            CommandNameInterface::class
        ));
    }
}
