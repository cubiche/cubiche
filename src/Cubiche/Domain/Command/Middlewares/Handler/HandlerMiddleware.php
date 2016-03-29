<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Command\Middlewares\Handler;

use Cubiche\Domain\Command\MiddlewareInterface;

/**
 * HandlerMiddleware class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class HandlerMiddleware implements MiddlewareInterface
{
    /**
     * {@inheritdoc}
     */
    public function execute($command, callable $next)
    {
        // TODO: Implement execute() method.
    }
}
