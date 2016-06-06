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

use Cubiche\Core\Bus\MessageInterface;
use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Bus\Exception\InvalidResolverException;
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

    public function __construct(array $resolvers)
    {
        $this->resolvers = new ArrayList();
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
    public function resolve(MessageInterface $message)
    {
        foreach ($this->resolvers as $resolver) {
            try {
                /* @var ResolverInterface $resolver */
                return $resolver->resolve($message);
            } catch (\Exception $exception) {
            }
        }

        throw NotFoundException::methodNameForObject($message);
    }
}
