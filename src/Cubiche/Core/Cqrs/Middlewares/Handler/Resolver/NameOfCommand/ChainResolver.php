<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Cqrs\Middlewares\Handler\Resolver\NameOfCommand;

use Cubiche\Core\Bus\Exception\InvalidResolverException;
use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\NameOfMessage\ChainResolver as NameOfMessageChainResolver;

/**
 * ChainResolver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ChainResolver extends NameOfMessageChainResolver implements ResolverInterface
{
    /**
     * {@inheritdoc}
     */
    protected function ensureResolver($resolver)
    {
        if (!$resolver instanceof ResolverInterface) {
            throw InvalidResolverException::forUnknownValue($resolver, ResolverInterface::class);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function notFoundException($command)
    {
        return NotFoundException::commandNameForObject($command);
    }
}
