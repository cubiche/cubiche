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
class FromQueryNamedResolver extends FromMessageNamedResolver
{
    /**
     * {@inheritdoc}
     */
    public function resolve(MessageInterface $message)
    {
        if ($message instanceof QueryNamedInterface) {
            return $message->named();
        }

        throw new \InvalidArgumentException(sprintf(
            'The object of type %s should implement the %s interface',
            get_class($message),
            QueryNamedInterface::class
        ));
    }
}
