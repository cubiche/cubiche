<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Middlewares\Handler\Resolver\QueryName;

use Cubiche\Core\Bus\Middlewares\Handler\Resolver\CommandName\CommandNamedResolver;
use Cubiche\Core\Bus\Query\QueryNamedInterface;

/**
 * QueryNamedResolver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class QueryNamedResolver extends CommandNamedResolver implements ResolverInterface
{
    /**
     * @return mixed
     */
    protected function getType()
    {
        return QueryNamedInterface::class;
    }
}
