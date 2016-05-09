<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Middlewares\Handler\Resolver\MethodName;

use Cubiche\Core\Bus\Command\CommandInterface;

/**
 * DefaultResolver class.
 *
 * Examples:
 *  - Cubiche\Application\Command\FooTaskCommand => $handler->handle()
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
        return 'handle';
    }
}
