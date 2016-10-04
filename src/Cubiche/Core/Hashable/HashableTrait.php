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
 * Hashable trait.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
trait HashableTrait
{
    /**
     * {@inheritdoc}
     */
    public function hashCode()
    {
        return HashCoder::defaultHashCode($this);
    }
}
