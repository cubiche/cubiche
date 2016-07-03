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
 * Resolver Visitor Method class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ResolverVisitorMethod implements ResolverVisitorMethodInterface
{
    /**
     * @var string
     */
    protected $prefix;

    /**
     * @param string $prefix
     */
    public function __construct($prefix = 'visit')
    {
        $this->prefix = empty($prefix) ? 'visit' : $prefix;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveVisiteeClass(\ReflectionMethod $method)
    {
        $parameters = $method->getParameters();
        if (empty($parameters)) {
            return;
        }

        /** @var \ReflectionParameter $visiteeParameter */
        $visiteeParameter = $parameters[0];

        return ($this->isVisiteeParameter($visiteeParameter) &&
            $method->getName() === $this->prefix.$visiteeParameter->getClass()->getShortName()) ?
            $visiteeParameter->getClass() : null;
    }

    /**
     * @param \ReflectionParameter $visiteeParameter
     *
     * @return bool
     */
    private function isVisiteeParameter(\ReflectionParameter $visiteeParameter)
    {
        return $visiteeParameter->getClass() !== null &&
            $visiteeParameter->getClass()->implementsInterface(VisiteeInterface::class);
    }
}
