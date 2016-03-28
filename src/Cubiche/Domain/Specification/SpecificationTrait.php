<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Specification;

/**
 * Specification Trait.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
trait SpecificationTrait
{
    /**
     * @param string $method
     * @param array  $arguments
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, array $arguments)
    {
        if ($method === 'and' || $method === 'or') {
            return call_user_func_array(array($this, $method.'X'), $arguments);
        }

        throw new \BadMethodCallException(\sprintf('Call to undefined method %s::%s', \get_class($this), $method));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationInterface::andX()
     */
    public function andX(SpecificationInterface $specification)
    {
        return new AndSpecification($this, $specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationInterface::orX()
     */
    public function orX(SpecificationInterface $specification)
    {
        return new OrSpecification($this, $specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationInterface::not()
     */
    public function not()
    {
        return new NotSpecification($this);
    }
}
