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

use Cubiche\Core\Bus\Middlewares\Handler\Resolver\NameOfMessage\FromClassNameResolver
    as NameOfMessageFromClassNameResolver;

/**
 * FromClassNameResolver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class FromClassNameResolver extends NameOfMessageFromClassNameResolver implements ResolverInterface
{
}
