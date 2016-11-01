<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Middlewares\Handler\Resolver\NameOfMessage;

use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Bus\MessageInterface;
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
    public function resolve(MessageInterface $message)
    {
        foreach ($this->resolvers as $resolver) {
            try {
                /* @var ResolverInterface $resolver */
                return $resolver->resolve($message);
            } catch (\Exception $exception) {
            }
        }

        throw $this->notFoundException($message);
    }

    /**
     * {@inheritdoc}
     */
    protected function addResolver(ResolverInterface $resolver)
    {
        $this->resolvers->add($resolver);
    }

    /**
     * @param MessageInterface $message
     *
     * @return NotFoundException
     */
    protected function notFoundException($message)
    {
        return NotFoundException::nameOfMessage($message);
    }
}
