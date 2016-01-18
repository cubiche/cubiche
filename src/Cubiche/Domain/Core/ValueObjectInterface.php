<?php

/**
 * This file is part of the cubiche/cubiche project.
 */
namespace Cubiche\Domain\Core;

/**
 * Value Object Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface ValueObjectInterface extends DomainObjectInterface
{
    /**
     * @param mixed $other
     *
     * @return bool
     */
    public function equals($other);

    /**
     * @return string
     */
    public function __toString();
}
