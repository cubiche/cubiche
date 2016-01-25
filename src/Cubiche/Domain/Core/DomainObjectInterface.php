<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
