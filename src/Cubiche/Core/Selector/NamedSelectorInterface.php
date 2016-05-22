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
 * Named Selector Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface NamedSelectorInterface extends SelectorInterface
{
    /**
     * @return string
     */
    public function name();
}
