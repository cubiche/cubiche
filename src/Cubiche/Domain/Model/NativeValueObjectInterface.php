<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Model;

/**
 * Native Value Object Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface NativeValueObjectInterface extends ValueObjectInterface
{
    /**
     * @param mixed $value
     *
     * @return static
     */
    public static function fromNative($value);

    /**
     * @return mixed
     */
    public function toNative();
}
