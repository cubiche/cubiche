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

use Cubiche\Core\Hashable\HashableInterface;

/**
 * Equatable Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface EquatableInterface extends HashableInterface
{
    /**
     * @param mixed $other
     *
     * @return bool
     */
    public function equals($other);
}
