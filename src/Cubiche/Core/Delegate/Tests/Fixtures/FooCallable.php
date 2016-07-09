<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Delegate\Tests\Fixtures;

use Cubiche\Core\Delegate\CallableInterface;

/**
 * Foo Callable class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class FooCallable implements CallableInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        return 'foo';
    }

    /**
     * {@inheritdoc}
     */
    public function invokeWith(array $args)
    {
        return \call_user_func_array($this, $args);
    }
}
