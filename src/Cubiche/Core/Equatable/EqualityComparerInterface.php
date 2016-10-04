<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Equatable;

use Cubiche\Core\Delegate\CallableInterface;
use Cubiche\Core\Hashable\HashCoderInterface;

/**
 * Equality Comparer interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface EqualityComparerInterface extends CallableInterface, HashCoderInterface
{
    /**
     * @param mixed $a
     * @param mixed $b
     *
     * @return bool
     */
    public function equals($a, $b);
}
