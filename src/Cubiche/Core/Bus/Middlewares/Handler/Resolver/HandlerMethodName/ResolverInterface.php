<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerMethodName;

use Cubiche\Core\Bus\Exception\NotFoundException;

/**
 * Resolver interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface ResolverInterface
{
    /**
     * Resolve the handler method name for a given class name.
     *
     * @param string $className
     *
     * @return string
     *
     * @throws NotFoundException
     */
    public function resolve($className);
}
