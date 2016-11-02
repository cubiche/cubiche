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
use Cubiche\Core\Collections\ArrayCollection\ArrayList;

/**
 * ChainResolver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ChainResolver implements ResolverInterface
{
    /**
     * @var ArrayList
     */
    protected $resolvers;

    /**
     * ChainResolver constructor.
     *
     * @param array $resolvers
     *
     * @throws \Cubiche\Core\Bus\Exception\InvalidLocatorException
     */
    public function __construct(array $resolvers)
    {
        $this->resolvers = new ArrayList();
        foreach ($resolvers as $resolver) {
            $this->addResolver($resolver);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($className)
    {
        foreach ($this->resolvers as $resolver) {
            try {
                /* @var ResolverInterface $resolver */
                return $resolver->resolve($className);
            } catch (\Exception $exception) {
            }
        }

        throw NotFoundException::handlerMethodNameForObject($className);
    }

    /**
     * {@inheritdoc}
     */
    protected function addResolver(ResolverInterface $resolver)
    {
        $this->resolvers->add($resolver);
    }
}
