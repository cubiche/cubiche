<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Command\Tests\Fixtures;

use Cubiche\Domain\Command\Middlewares\Handler\Resolver\MethodName\ResolverInterface;

/**
 * InvalidMethodNameResolver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InvalidMethodNameResolver implements ResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolve($command)
    {
        throw new \Exception('Method name not found');
    }
}
