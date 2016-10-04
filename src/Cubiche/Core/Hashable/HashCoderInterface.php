<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Hashable;

/**
 * Hash Coder interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface HashCoderInterface
{
    /**
     * Return hash id for given value.
     *
     * @param mixed $value
     *
     * @return string
     */
    public function hashCode($value);
}
