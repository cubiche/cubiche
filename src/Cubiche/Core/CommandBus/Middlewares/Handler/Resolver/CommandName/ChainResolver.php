<?php

/**
 * This file is part of the Cubiche/CommandBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\CommandBus\Middlewares\Handler\Resolver\CommandName;

use Cubiche\Core\Collections\ArrayCollection;
use Cubiche\Core\CommandBus\Exception\NotFoundException;
use Cubiche\Core\CommandBus\Exception\InvalidResolverException;

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
     * @param ResolverInterface[] $resolvers
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

        throw NotFoundException::commandNameForCommand($command);
    }
}
