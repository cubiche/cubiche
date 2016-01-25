<?php

/**
 * This file is part of the cubiche/cubiche project.
 */
namespace Cubiche\Domain\Core;

/**
 * Entity Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface EntityInterface extends DomainObjectInterface
{
    /**
     * @return IdInterface
     */
    public function id();
}
