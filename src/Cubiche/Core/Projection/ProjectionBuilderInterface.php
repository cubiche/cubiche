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
 * Projection Builder Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface ProjectionBuilderInterface extends ProjectionWrapperInterface
{
    /**
     * @param string $property
     * @param mixed  $value
     */
    public function set($property, $value);

    /**
     * @param string $property
     */
    public function remove($property);

    /**
     * @return \Iterator<string>
     */
    public function properties();

    /**
     * @param ProjectionWrapperInterface $projection
     * @param string[]                   $exclude
     *
     * @return \Cubiche\Core\Projection\ProjectionBuilderInterface
     */
    public function join(ProjectionWrapperInterface $projection, $exclude = array());
}
