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

/**
 * DefaultResolver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DefaultResolver implements ResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolve(CommandInterface $command)
    {
        return get_class($command);
    }
}
