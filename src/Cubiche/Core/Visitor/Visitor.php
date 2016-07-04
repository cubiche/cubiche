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
 * Abstract Visitor Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class Visitor extends LinkedVisitor
{
    /**
     * @param ResolverVisitorMethodInterface $resolver
     */
    public function __construct(ResolverVisitorMethodInterface $resolver = null)
    {
        parent::__construct(null, $resolver);
    }
}
