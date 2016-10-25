<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Cqrs\Middlewares\Handler\Resolver\NameOfQuery;

use Cubiche\Core\Bus\MessageInterface;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\NameOfMessage\FromMessageNamedResolver;
use Cubiche\Core\Cqrs\Query\QueryNamedInterface;

/**
 * FromQueryNamedResolver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class FromQueryNamedResolver extends FromMessageNamedResolver implements ResolverInterface
{
    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return QueryNamedInterface::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getName(MessageInterface $message)
    {
        /* @var QueryNamedInterface $message */
        return $message->queryName();
    }
}
