<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Middlewares\Handler\Resolver\CommandName;

use Cubiche\Core\Bus\Command\CommandInterface;
use Cubiche\Core\Bus\Command\CommandNamedInterface;

/**
 * CommandNamedResolver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CommandNamedResolver implements ResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolve(CommandInterface $command)
    {
        $type = $this->getType();
        if ($command instanceof $type) {
            return $command->name();
        }

        throw new \InvalidArgumentException(sprintf(
            'The object of type %s should implement the %s interface',
            get_class($command),
            $type
        ));
    }

    /**
     * @return mixed
     */
    protected function getType()
    {
        return CommandNamedInterface::class;
    }
}
