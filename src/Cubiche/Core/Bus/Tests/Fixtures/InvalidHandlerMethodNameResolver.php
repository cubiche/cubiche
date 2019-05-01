<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Tests\Fixtures;

use Cubiche\Core\Bus\Handler\MethodName\HandlerMethodNameResolverInterface;
use Cubiche\Core\Bus\MessageInterface;

/**
 * InvalidHandlerMethodNameResolver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InvalidHandlerMethodNameResolver implements HandlerMethodNameResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolve(MessageInterface $message): string
    {
        throw new \Exception('Method name not found');
    }
}
