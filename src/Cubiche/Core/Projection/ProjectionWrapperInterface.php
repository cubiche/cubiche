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
 * Projection Wrapper Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface ProjectionWrapperInterface
{
    /**
     * @param string $property
     *
     * @return bool
     */
    public function has($property);

    /**
     * @param string $property
     *
     * @return mixed
     */
    public function get($property);

    /**
     * @return object
     */
    public function projection();
}
