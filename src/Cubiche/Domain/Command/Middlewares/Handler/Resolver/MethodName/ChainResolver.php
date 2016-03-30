<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Command\Middlewares\Handler\Resolver\MethodName;

use Cubiche\Domain\Collections\ArrayCollection;
use Cubiche\Domain\Command\Exception\NotFoundException;
use Cubiche\Domain\Command\Exception\InvalidResolverException;

/**
 * ChainResolver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ChainResolver implements ResolverInterface
{
    /**
     * @var ArrayCollection
     */
    protected $resolvers;

    /**
     * ChainResolver constructor.
     *
     * @param array $resolvers
     */
    public function __construct(array $resolvers)
    {
        $this->resolvers = new ArrayCollection();
        foreach ($resolvers as $resolver) {
            if (!$resolver instanceof ResolverInterface) {
                throw InvalidResolverException::forUnknownValue($resolver, ResolverInterface::class);
            }

            $this->resolvers->add($resolver);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($command)
    {
        foreach ($this->resolvers as $resolver) {
            try {
                /* @var ResolverInterface $resolver */
                return $resolver->resolve($command);
            } catch (\Exception $exception) {
            }
        }

        throw NotFoundException::methodNameForCommand($command);
    }
}
