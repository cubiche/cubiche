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

use Cubiche\Core\Hashable\Hashable;

/**
 * Abstract Equatable class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class Equatable extends Hashable implements EquatableInterface
{
    /**
     * {@inheritdoc}
     */
    public function equals($other)
    {
        return $this === $other;
    }
}
