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
 * Object Projector Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ObjectProjector implements ProjectorInterface
{
    /**
     * {@inheritdoc}
     */
    public function project($value)
    {
        yield new ProjectionWrapper($value);
    }
}
