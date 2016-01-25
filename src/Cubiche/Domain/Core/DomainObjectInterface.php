<?php

/**
 * This file is part of the cubiche/cubiche project.
 */
namespace Cubiche\Domain\Core;

/**
 * Domain Object Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface DomainObjectInterface
{
    /**
     * @param DomainObjectInterface $other
     *
     * @return bool
     */
    public function equals($other);
}
