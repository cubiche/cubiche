<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Visitor;

/**
 * Resolver Visitor Method interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface ResolverVisitorMethodInterface
{
    /**
     * @param \ReflectionMethod $method
     *
     * @return \ReflectionClass
     */
    public function resolveVisiteeClass(\ReflectionMethod $method);
}
