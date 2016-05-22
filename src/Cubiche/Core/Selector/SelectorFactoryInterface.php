<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Selector;

/**
 * Selector Factory Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface SelectorFactoryInterface
{
    /**
     * @param string $selectorClass
     * @param string $selectorName
     */
    public function addSelector($selectorClass, $selectorName = null);

    /**
     * @param string $namespace
     */
    public function addNamespace($namespace);

    /**
     * @param string $selectorName
     * @param array  $arguments
     *
     * @return SelectorInterface
     */
    public function create($selectorName, array $arguments = array());
}
