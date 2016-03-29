<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Command\Middlewares\Handler\Resolver\ClassName;

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
     * @var ResolverInterface[]
     */
    protected $resolvers;

    /**
     * @param ResolverInterface[] $resolvers
     */
    public function __construct(array $resolvers)
    {
        $this->resolvers = $resolvers;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($command)
    {
        while ($resolver = array_shift($this->resolvers)) {
            if (!$resolver instanceof ResolverInterface) {
                throw InvalidResolverException::forHandler($resolver);
            }

            try {
                return $resolver->resolve($command);
            } catch (\Exception $exception) {
            }
        }

        throw NotFoundException::handlerForCommand($command);
    }
}
