<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Projection;

/**
 * Projection Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class Projection implements ProjectionInterface
{
    /**
     * {@inheritdoc}
     */
    public function join(ProjectionInterface $projection)
    {
        return new JoinProjection($this, $projection);
    }
}
