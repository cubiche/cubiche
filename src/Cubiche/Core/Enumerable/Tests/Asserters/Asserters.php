<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Enumerable\Tests\Asserters;

/**
 * Asserters trait.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
trait Asserters
{
    /**
     * @return \Cubiche\Core\Enumerable\Tests\Asserters\EnumerableAsserter
     */
    public function enumerable()
    {
        return static::__call(__FUNCTION__, func_get_args());
    }

    /**
     * @return \Cubiche\Core\Enumerable\Tests\Asserters\IteratorAsserter
     */
    public function iterator()
    {
        return static::__call(__FUNCTION__, func_get_args());
    }
}
