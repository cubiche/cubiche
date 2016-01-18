<?php

/**
 * This file is part of the Jadddp/core project.
 */
namespace Cubiche\Domain\Core;

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
